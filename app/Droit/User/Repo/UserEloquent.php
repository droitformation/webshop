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

        return $this->user->with(['adresses','orders','inscriptions'])->findOrFail($id);
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
        $user = $this->user->where('provider_id', '=', $userData->id)->first();

        if(!$user)
        {
            $user = $this->user->create([
                'provider_id' => $userData->id,
                'name'        => $userData->name,
                'username'    => $userData->nickname,
                'email'       => $userData->email,
            ]);
        }

        $this->checkIfUserNeedsUpdating($userData, $user);

        return $user;
    }

    public function checkIfUserNeedsUpdating($userData, $user)
    {

        $socialData = [
            'email'    => $userData->email,
            'name'     => $userData->name,
            'username' => $userData->nickname,
        ];
        $dbData = [
            'email'    => $user->email,
            'name'     => $user->name,
            'username' => $user->username,
        ];

        $update = array_diff($socialData, $dbData);

        if (!empty($update))
        {
            $user->email    = $userData->email;
            $user->name     = $userData->name;
            $user->username = $userData->nickname;
            $user->save();
        }
    }

}
