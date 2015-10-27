<?php namespace App\Droit\Specialisation\Repo;

use App\Droit\Specialisation\Repo\SpecialisationInterface;
use App\Droit\Specialisation\Entities\Specialisation as M;

class SpecialisationEloquent implements SpecialisationInterface{

    protected $specialisation;

    public function __construct(M $specialisation)
    {
        $this->specialisation = $specialisation;
    }

    public function getAll(){

        return $this->specialisation->all();
    }

    public function find($id){

        return $this->specialisation->find($id);
    }

    public function search($term, $like = null)
    {
        if($like)
        {
            return $this->specialisation->where('title','LIKE', '%'.$term.'%')->get();
        }

        return $this->specialisation->where('title','=', $term)->get()->first();
    }

    public function create(array $data){

        $specialisation = $this->specialisation->create(array(
            'title' => $data['title']
        ));

        if( ! $specialisation )
        {
            return false;
        }

        return $specialisation;

    }

    public function update(array $data){

        $specialisation = $this->specialisation->findOrFail($data['id']);

        if( ! $specialisation )
        {
            return false;
        }

        $specialisation->fill($data);

        $specialisation->save();

        return $specialisation;
    }

    public function delete($id){

        $specialisation = $this->specialisation->find($id);

        return $specialisation->delete();

    }
}
