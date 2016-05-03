<?php namespace App\Droit\Profession\Repo;

use App\Droit\Profession\Repo\ProfessionInterface;
use App\Droit\Profession\Entities\Profession as M;

class ProfessionEloquent implements ProfessionInterface{

    protected $profession;

    public function __construct(M $profession)
    {
        $this->profession = $profession;
    }

    public function getAll(){

        return $this->profession->orderBy('title','ASC')->get();
    }

    public function find($id){

        return $this->profession->find($id);
    }

    public function create(array $data){

        $profession = $this->profession->create(array(
            'title' => $data['title']
        ));

        if( ! $profession )
        {
            return false;
        }

        return $profession;

    }

    public function update(array $data){

        $profession = $this->profession->findOrFail($data['id']);

        if( ! $profession )
        {
            return false;
        }

        $profession->title = $data['title'];

        $profession->save();

        return $profession;
    }

    public function delete($id){

        $profession = $this->profession->find($id);

        return $profession->delete($id);

    }
}
