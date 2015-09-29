<?php namespace App\Droit\Organisateur\Repo;

use App\Droit\Organisateur\Repo\OrganisateurInterface;
use App\Droit\Organisateur\Entities\Organisateur as M;

class OrganisateurEloquent implements OrganisateurInterface{

    protected $organisateur;

    public function __construct(M $organisateur)
    {
        $this->organisateur = $organisateur;
    }

    public function getAll(){

        return $this->organisateur->all();
    }

    public function centres(){

        return $this->organisateur->where('centre','=',1)->get();
    }

    public function find($id){

        return $this->organisateur->find($id);
    }

    public function create(array $data){

        $organisateur = $this->organisateur->create(array(
            'name'        => $data['name'],
            'description' => $data['description'],
            'url'         => $data['name'],
            'logo'        => $data['logo'],
            'centre'      => $data['centre']
        ));

        if( ! $organisateur )
        {
            return false;
        }

        return $organisateur;

    }

    public function update(array $data){

        $organisateur = $this->organisateur->findOrFail($data['id']);

        if( ! $organisateur )
        {
            return false;
        }

        $organisateur->fill($data);

        $organisateur->save();

        return $organisateur;
    }

    public function delete($id){

        $organisateur = $this->organisateur->find($id);

        return $organisateur->delete();

    }
}
