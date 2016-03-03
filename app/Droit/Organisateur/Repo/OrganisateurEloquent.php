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

        return $this->organisateur->where('centre','=',1)->orderBy('name')->get();
    }

    public function find($id){

        return $this->organisateur->find($id);
    }

    public function create(array $data){

        $organisateur = $this->organisateur->create(array(
            'name'        => $data['name'],
            'centre'      => $data['centre'],
            'description' => isset($data['description']) ? $data['description'] : '',
            'adresse'     => isset($data['adresse']) ? $data['adresse'] : '',
            'tva'         => isset($data['tva']) ? $data['tva'] : '',
            'url'         => isset($data['url']) ? $data['url'] : '',
            'logo'        => isset($data['logo']) ? $data['logo'] : '',
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
