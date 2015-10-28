<?php namespace App\Droit\User\Repo;

use App\Droit\User\Repo\UserInterface;
use App\Droit\User\Entities\User as M;

class UserEloquent implements UserInterface{

    protected $user;

    public function __construct(M $user)
    {
        $this->user = $user;
    }

    public function getAll(){

        return $this->user->all();
    }

    public function find($id){

        return $this->user->with(['adresses','orders','inscriptions','roles'])->findOrFail($id);
    }

    public function search($term){

        return $this->user->where('email', 'like', '%'.$term.'%')
            ->orWhere('first_name', 'like', '%'.$term.'%')
            ->orWhere('last_name', 'like', '%'.$term.'%')
            ->get();
    }

    public function create(array $data){

        $user = $this->user->create(array(
            'first_name'     => $data['first_name'],
            'last_name'      => $data['last_name'],
            'email'          => $data['email'],
            'password'       => bcrypt($data['password']),
            'created_at'     => date('Y-m-d G:i:s'),
            'updated_at'     => date('Y-m-d G:i:s')
        ));

        if( ! $user )
        {
            return false;
        }

        return $user;

    }

    public function update(array $data){

        $user = $this->user->findOrFail($data['id']);

        if( ! $user )
        {
            return false;
        }

        $user->fill($data);

        $user->updated_at = date('Y-m-d G:i:s');

        $user->save();

        return $user;
    }

    public function delete($id){

        $user = $this->user->find($id);

        return $user->delete($id);
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
