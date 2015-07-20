<?php namespace App\Droit\Location\Repo;

use App\Droit\Location\Repo\LocationInterface;
use App\Droit\Location\Entities\Location as M;

class LocationEloquent implements LocationInterface{

    protected $location;

    public function __construct(M $location)
    {
        $this->location = $location;
    }

    public function getAll(){

        return $this->location->all();
    }

    public function find($id){

        return $this->location->find($id);
    }

    public function create(array $data){

        $location = $this->location->create(array(
            'name'    => $data['name'],
            'adresse' => $data['adresse'],
            'url'     => $data['name'],
            'map'     => $data['map']
        ));

        if( ! $location )
        {
            return false;
        }

        return $location;

    }

    public function update(array $data){

        $location = $this->location->findOrFail($data['id']);

        if( ! $location )
        {
            return false;
        }

        $location->fill($data);

        $location->save();

        return $location;
    }

    public function delete($id){

        $location = $this->location->find($id);

        return $location->delete();

    }
}
