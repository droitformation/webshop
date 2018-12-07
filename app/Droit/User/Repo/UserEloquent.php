<?php namespace App\Droit\User\Repo;

use App\Droit\User\Repo\UserInterface;
use App\Droit\User\Entities\User as M;
use App\Events\SubscriberEmailUpdated;

class UserEloquent implements UserInterface{

    protected $user;

    public function __construct(M $user)
    {
        $this->user = $user;
    }

    public function getAll()
    {
        return $this->user->with(['adresses'])->orderBy('last_name')->orderBy('first_name')->paginate(50);
    }

    public function getPaginate()
    {
        return $this->user->with(['adresses'])->orderBy('created_at','DESC')->take(5)->get();
    }

    public function find($id)
    {
        return $this->user->with(['adresses','orders','inscriptions','roles','inscription_groupes','abos'])->findOrFail($id);
    }

    public function findWithTrashed($id){
        return $this->user->with(['adresses','orders','inscriptions','roles','inscription_groupes'])->withTrashed()->find($id);
    }

    public function findByEmail($email)
    {
        $exist = $this->user->where('email', 'like', '%'.$email.'%')->get();
        $exist = $exist->reject(function ($user) {
            return empty($user->email);
        });

        return (!$exist->isEmpty() ? $exist->first() : null);
    }

    public function findByName($name)
    {
        $exist = $this->user->where(function ($query) use($name) {

                $query->where('first_name', 'like', '%'.$name['first_name'].'%')
                      ->where('last_name', 'like', '%'.$name['last_name'].'%');

            })->whereHas('adresses', function ($query) use ($name) {

                if(isset($name['canton_id']))
                {
                    $query->where('canton_id', $name['canton_id']);
                }

            })->get();

        return (!$exist->isEmpty() ? $exist->first() : null);
    }

    public function search($term){

        $terms = explode(' ',trim($term));

        if(count($terms) > 1)
        {
            return $this->user
                ->where('email', 'like', '%'.$term.'%')
                ->orWhere('first_name', 'like', '%'.$term.'%')
                ->orWhere('last_name', 'like', '%'.$term.'%')
                ->orWhere('company', 'like', '%'.$term.'%')
                ->orWhere(function ($query) use($term,$terms) {
                    if(count($terms) == 2)
                    {
                        $query->where(function ($query1) use ($terms) {
                            $query1->where('first_name', 'like', '%'.$terms[0].'%')->where('last_name', 'like', '%'.$terms[1].'%');
                        })->orWhere(function ($query2) use ($terms){
                            $query2->where('first_name', 'like', '%'.$terms[1].'%')->where('last_name', 'like', '%'.$terms[0].'%');
                        })->orWhere(function ($query3) use ($terms){
                            $query3->where('company', 'like', '%'.$terms[1].'%')->where('company', 'like', '%'.$terms[0].'%');
                        });

                    }

                    if(count($terms) == 3)
                    {
                        $query->where(function ($query1) use ($terms) {
                            $query1->where('first_name', 'like', '%'.$terms[0].' '.$terms[1].'%')->where('last_name', 'like', '%'.$terms[2].'%');
                        })->orWhere(function ($query2) use ($terms){
                            $query2->where('first_name', 'like', '%'.$terms[0].'%')->where('last_name', 'like', '%'.$terms[1].' '.$terms[2].'%');
                        });
                    }
                })->get();
        }

        return $this->user->where('email', 'like', '%'.$term.'%')
            ->orWhere('first_name', 'like', '%'.$term.'%')
            ->orWhere('last_name', 'like', '%'.$term.'%')
            ->orWhere('company', 'like', '%'.$term.'%')
            ->get();
    }

    public function searchSimple($terms)
    {
        return $this->user->with(['adresses'])
                            ->searchEmail($terms['email'])
                            ->searchLastName($terms['last_name'])
                            ->searchFirstName($terms['first_name'])
                            ->get();
    }

    public function getDeleted($terms = [], $operator = null)
    {
        $user = $this->user->withTrashed()->with(['orders','inscriptions','adresses']);

        if(!empty($terms)) {
            $operator = ($operator == 'and' ? 'where' : 'orWhere');
            $user->where(function ($query) use ($terms, $operator) {
                foreach($terms as $term){
                    $query->$operator($term['column'],'LIKE','%'.$term['value'].'%');
                }
            });
        }

        return $user->orderBy('last_name','ASC')->get();
    }

    public function getMultiple($ids)
    {
        return $this->user->withTrashed()->with(['adresses_and_trashed'])->whereIn('id',$ids)->orderBy('last_name','ASC')->get();
    }

    public function create(array $data){

        $user = $this->user->create(array(
            'first_name'     => isset($data['first_name']) && !empty($data['first_name']) ? $data['first_name'] : null,
            'last_name'      => isset($data['last_name']) && !empty($data['last_name']) ? $data['last_name'] : null,
            'company'        => isset($data['company']) && !empty($data['company']) ? $data['company'] : null,
            'username'       => isset($data['username']) && !empty($data['username']) ? $data['username'] : null,
            'email'          => $data['email'],
            'password'       => bcrypt($data['password']),
            'created_at'     => date('Y-m-d G:i:s'),
            'updated_at'     => date('Y-m-d G:i:s')
        ));

        if(isset($data['role']) && $data['role'] > 0)
        {
            $user->roles()->attach($data['role']);
        }

        if( ! $user )
        {
            return false;
        }

        return $user;

    }

    public function update(array $data){

        $user = $this->user->findOrFail($data['id']);

        if( ! $user ) {
            return false;
        }

        // If email change update newsletter subscriptions
        if(isset($data['email']) && ($data['email'] != $user->email)){
            event(new \App\Events\EmailAccountUpdated($user,$data['email']));
            $user->email = $data['email'];
        }

        //$user->fill($data);
        $user->first_name = isset($data['first_name']) && !empty($data['first_name']) ? $data['first_name'] : null;
        $user->last_name = isset($data['last_name']) && !empty($data['last_name']) ? $data['last_name'] : null;
        $user->company = isset($data['company']) && !empty($data['company']) ? $data['company'] : null;

        if(isset($data['username']) && !empty($data['username']) && ($data['email'] != $data['username'])){
            $user->username = $data['username'];
        }

        if(!isset($data['username'])){
            $user->username = $data['email'];
        }

        if(isset($data['password']) && !empty($data['password'])) {
            $user->password = bcrypt($data['password']);
        }

        if(isset($data['role'])) {
            if($data['role'] != 10){
                $user->roles()->sync([$data['role']]);
            }
            else{
                $user->roles()->detach();
            }
        }

        $user->updated_at = date('Y-m-d G:i:s');

        $user->save();

        return $user;
    }

    public function delete($id){

        $user = $this->user->find($id);

        if($user){
            return $user->delete($id);
        }

        return true;
    }

    public function restore($id){

        $restore = $this->user->withTrashed()->find($id);
        $restore->restore();

        return $restore;
    }

    public function findByUserNameOrCreate($userData)
    {
        $user = $this->user->where('email', '=', $userData->email)->first();

        if(!$user)
        {
            $user = $this->user->create([
                'provider_id' => $userData->id,
                'provider'    => $userData->provider,
                'first_name'  => $userData->first_name,
                'last_name'   => $userData->last_name,
                'email'       => $userData->email,
            ]);
        }

        $this->checkIfUserNeedsUpdating($userData, $user);

        return $user;
    }

    public function checkIfUserNeedsUpdating($userData, $user)
    {

        $socialData = [
            'email'      => $userData->email,
            'first_name' => $userData->first_name,
            'last_name'  => $userData->last_name,
        ];

        $dbData = [
            'email'      => $user->email,
            'first_name' => $user->first_name,
            'last_name'  => $user->last_name,
        ];

        $update = array_diff($socialData, $dbData);

        if (!empty($update))
        {
            $user->email      = $userData->email;
            $user->first_name = $userData->first_name;
            $user->last_name  = $userData->last_name;
            $user->save();
        }
    }

    public function get_ajax($draw, $start, $length, $sortCol, $sortDir, $search){

        $columns = ['id','nom','email','adresse'];

        $iTotal  = $this->user->all()->count();

        if($search)
        {
            $data = $this->user->where('email','LIKE','%'.$search.'%')
                ->with(['adresses'])
                ->orderBy($columns[$sortCol], $sortDir)
                ->take($length)
                ->skip($start)
                ->get();

            $recordsTotal = $data->count();
        }
        else
        {
            $data = $this->user->with(['adresses'])
                ->orderBy($columns[$sortCol], $sortDir)
                ->take($length)
                ->skip($start)
                ->get();

            $recordsTotal = $iTotal;
        }

        $output = array(
            "draw"            => $draw,
            "recordsTotal"    => $iTotal,
            "recordsFiltered" => $recordsTotal,
            "data"            => []
        );

        foreach($data as $user)
        {
            $row = [];

            $row['id']      = '<a class="btn btn-sky btn-sm" href="'.url('admin/user/'.$user->id).'">&Eacute;diter</a>';
            $row['nom']     = $user->name;
            $row['email']   = $user->email;
            $row['adresse'] = '';

            if( !$user->adresses->isEmpty())
            {
                $html = '<ul class="list-group" style="margin-bottom: 0;">';
                foreach ($user->adresses as $adresse)
                {
                    $html .= '<li class="list-group-item">';
                    $html .=  $adresse->type_title;
                    $html .= '<a href="'.url('admin/adresse/'.$adresse->id).'" class="btn btn-xs btn-info pull-right">éditer</a>';
                    $html .= '</li>';
                }
                $html .= '</ul>';

                $row['adresse'] = $html;
            }

            $row['delete']  = '<form action="'.url('admin/user/'.$user->id).'" method="POST">'.csrf_field().'<input type="hidden" name="_method" value="DELETE">';
            $row['delete'] .= '<button data-what="supprimer" data-action="Abonné '.$user->name.'" class="btn btn-danger btn-xs deleteAction pull-right">Supprimer</button>';
            $row['delete'] .= '</form>';

            //$row = array_values($row);

            $output['data'][] = $row;
        }

        return json_encode( $output );

    }

}
