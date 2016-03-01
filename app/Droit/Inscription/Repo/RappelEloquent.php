<?php namespace App\Droit\Inscription\Repo;

use App\Droit\Inscription\Repo\RappelInterface;
use App\Droit\Inscription\Entities\Rappel as M;

class RappelEloquent implements RappelInterface{

    protected $rappel;

    public function __construct(M $rappel)
    {
        $this->rappel = $rappel;
    }

    public function getAll(){

        return $this->rappel->with(['inscription'])->groupBy('group_id')->get();
    }

    public function find($id){

        return $this->rappel->with(['inscription'])->find($id);
    }

    public function create(array $data){

        $rappel = $this->rappel->create(array(
            'inscription_id' => $data['inscription_id'],
            'user_id'        => (isset($data['user_id']) ? $data['user_id'] : null),
            'group_id'       => (isset($data['group_id']) ? $data['group_id'] : null)
        ));

        if( ! $rappel )
        {
            return false;
        }

        return $rappel;

    }

    public function update(array $data){

        $rappel = $this->rappel->findOrFail($data['id']);

        if( ! $rappel )
        {
            return false;
        }

        $rappel->fill($data);
        $rappel->save();

        return $rappel;
    }

    public function delete($id){

        $rappel = $this->rappel->find($id);

        return $rappel->delete();
    }
}
