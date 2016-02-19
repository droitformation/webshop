<?php namespace App\Droit\User\Repo;

use App\Droit\User\Repo\UserInterface;
use App\Droit\User\Entities\User_duplicates as M;

class DuplicateEloquent implements DuplicateInterface{

    protected $user;

    public function __construct(M $user)
    {
        $this->user = $user;
    }

    public function getAll()
    {
        return $this->user->with(['user'])->get();
    }

    public function find($id){

        return $this->user->with(['adresses','orders','inscriptions','user'])->findOrFail($id);
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

        $user->save();

        return $user;
    }

    public function delete($id){

        $user = $this->user->find($id);

        return $user->delete($id);
    }

}
