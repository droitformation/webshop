<?php namespace App\Droit\Option\Repo;

use App\Droit\Option\Repo\OptionInterface;
use App\Droit\Option\Entities\Option as M;

class OptionEloquent implements OptionInterface{

    protected $option;
    protected $helper;

    public function __construct(M $option)
    {
        $this->option = $option;
        $this->helper = new \App\Droit\Helper\Helper();
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

        if(isset($data['groupe']))
        {
            foreach($data['groupe'] as $choice)
            {
                $option->groupe()->create([
                    'text'        => $choice['text'],
                    'colloque_id' => $data['colloque_id'],
                    'option_id'   => $option->id
                ]);
            }
        }

        return $option;

    }

    public function update(array $data){

        $option = $this->option->findOrFail($data['id']);

        if(!$option)
        {
            return false;
        }

        if(isset($data['groupe']))
        {
            $option->groupe()->delete();

            foreach($data['groupe'] as $choice)
            {
                $option->groupe()->create([
                    'text'        => $choice['text'],
                    'colloque_id' => $data['colloque_id'],
                    'option_id'   => $option->id
                ]);
            }
        }

        $option->fill($data);
        $option->save();

        return $option;
    }

    public function delete($id){

        $option = $this->option->find($id);
        $option->groupe()->delete();

        return $option->delete();
    }
}
