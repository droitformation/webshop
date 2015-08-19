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

}
