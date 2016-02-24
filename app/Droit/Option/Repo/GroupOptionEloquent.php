<?php namespace App\Droit\Option\Repo;

use App\Droit\Option\Repo\GroupOptionInterface;
use App\Droit\Option\Entities\OptionGroupe as M;

class GroupOptionEloquent implements GroupOptionInterface{

    protected $group;

    public function __construct(M $group)
    {
        $this->group = $group;
    }

    public function getAll(){

        return $this->group->all();
    }

    public function find($id){

        return $this->group->find($id);
    }

    public function create(array $data){

        $group = $this->group->create(array(
            'colloque_id' => $data['colloque_id'],
            'option_id'   => $data['option_id'],
            'text'        => $data['text']
        ));

        if( ! $group )
        {
            return false;
        }

        return $group;

    }

    public function update(array $data){

        $group = $this->group->findOrFail($data['id']);

        if( ! $group )
        {
            return false;
        }

        $group->fill($data);
        $group->save();

        return $group;
    }

    public function delete($id){

        $group = $this->group->find($id);

        return $group->delete();

    }
}
