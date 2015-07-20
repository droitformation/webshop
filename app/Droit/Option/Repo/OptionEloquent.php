<?php namespace App\Droit\Option\Repo;

use App\Droit\Option\Repo\OptionInterface;
use App\Droit\Option\Entities\Option as M;

class OptionEloquent implements OptionInterface{

    protected $option;

    public function __construct(M $option)
    {
        $this->option = $option;
    }

    public function getAll(){

        return $this->option->all();
    }

    public function find($id){

        return $this->option->find($id);
    }

    public function create(array $data){

        $option = $this->option->create(array(
            'colloque_id' => $data['colloque_id'],
            'title'       => $data['title'],
            'type'        => $data['type']
        ));

        if( ! $option )
        {
            return false;
        }

        return $option;

    }

    public function update(array $data){

        $option = $this->option->findOrFail($data['id']);

        if( ! $option )
        {
            return false;
        }

        $option->fill($data);

        $option->save();

        return $option;
    }

    public function delete($id){

        $option = $this->option->find($id);

        return $option->delete();

    }
}
