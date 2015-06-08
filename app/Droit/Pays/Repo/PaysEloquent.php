<?php namespace App\Droit\Pays\Repo;

use App\Droit\Pays\Repo\PaysInterface;
use App\Droit\Pays\Entities\Pays as M;

class PaysEloquent implements PaysInterface{

    protected $pays;

    public function __construct(M $pays)
    {
        $this->pays = $pays;
    }

    public function getAll(){

        return $this->pays->all();
    }

    public function find($id){

        return $this->pays->find($id);
    }

    public function create(array $data){

        $pays = $this->pays->create(array(
            'title' => $data['title'],
            'code'  => $data['code']
        ));

        if( ! $pays )
        {
            return false;
        }

        return $pays;

    }

    public function update(array $data){

        $pays = $this->pays->findOrFail($data['id']);

        if( ! $pays )
        {
            return false;
        }

        $pays->title = $data['title'];
        $pays->code  = $data['code'];

        $pays->save();

        return $pays;
    }

    public function delete($id){

        $pays = $this->pays->find($id);

        return $pays->delete();

    }
}
