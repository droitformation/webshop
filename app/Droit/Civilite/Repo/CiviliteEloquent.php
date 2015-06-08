<?php namespace App\Droit\Civilite\Repo;

use App\Droit\Civilite\Repo\CiviliteInterface;
use App\Droit\Civilite\Entities\Civilite as M;

class CiviliteEloquent implements CiviliteInterface{

    protected $civilite;

    public function __construct(M $civilite)
    {
        $this->civilite = $civilite;
    }

    public function getAll(){

        return $this->civilite->all();
    }

    public function find($id){

        return $this->civilite->find($id);
    }

    public function create(array $data){

        $civilite = $this->civilite->create(array(
            'title' => $data['title'],
            'code'  => $data['code']
        ));

        if( ! $civilite )
        {
            return false;
        }

        return $civilite;

    }

    public function update(array $data){

        $civilite = $this->civilite->findOrFail($data['id']);

        if( ! $civilite )
        {
            return false;
        }

        $civilite->title = $data['title'];
        $civilite->code  = $data['code'];

        $civilite->save();

        return $civilite;
    }

    public function delete($id){

        $civilite = $this->civilite->find($id);

        return $civilite->delete();

    }
}
