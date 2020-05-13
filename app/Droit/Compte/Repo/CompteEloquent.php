<?php namespace App\Droit\Compte\Repo;

use App\Droit\Compte\Repo\CompteInterface;
use App\Droit\Compte\Entities\Compte as M;

class CompteEloquent implements CompteInterface{

    protected $compte;

    public function __construct(M $compte)
    {
        $this->compte = $compte;
    }

    public function getAll(){

        return $this->compte->orderBy('compte', 'asc')->get();
    }

    public function find($id){

        return $this->compte->find($id);
    }

    public function create(array $data){

        $compte = $this->compte->create(array(
            'motif'   => $data['motif'],
            'adresse' => $data['adresse'],
            'centre'  => str_replace(' ', '', $data['centre']),
            'compte'  => $data['compte']
        ));

        if( ! $compte ) {
            return false;
        }

        return $compte;
    }

    public function update(array $data){

        $compte = $this->compte->findOrFail($data['id']);

        if( ! $compte ) {
            return false;
        }

        $compte->fill($data);
        $compte->centre = str_replace(' ', '', $data['centre']);

        $compte->save();

        return $compte;
    }

    public function delete($id){

        $compte = $this->compte->find($id);

        return $compte->delete();

    }
}
