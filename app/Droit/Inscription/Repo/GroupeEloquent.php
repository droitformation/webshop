<?php namespace App\Droit\Inscription\Repo;

use App\Droit\Inscription\Repo\GroupeInterface;
use App\Droit\Inscription\Entities\Groupe as M;

class GroupeEloquent implements GroupeInterface{

    protected $groupe;

    public function __construct(M $groupe)
    {
        $this->groupe = $groupe;
    }

    public function getAll(){

        return $this->groupe->with(['user'])->get();
    }

    public function getRappels($id)
    {
        return $this->groupe->with(['inscriptions'])->where('colloque_id','=',$id)->whereHas('inscriptions', function ($query) {
            $query->whereNull('payed_at');
        })->paginate(20);
    }

    public function find($id){

        return $this->groupe->with(['user','inscriptions'])->find($id);
    }

    public function create(array $data){

        $groupe = $this->groupe->create(array(
            'colloque_id'     => $data['colloque_id'],
            'user_id'         => $data['user_id'],
        ));

        if( ! $groupe )
        {
            return false;
        }

        return $groupe;

    }

    public function update(array $data){

        $groupe = $this->groupe->findOrFail($data['id']);

        if( ! $groupe )
        {
            return false;
        }

        $groupe->fill($data);
        $groupe->save();

        return $groupe;
    }

    public function delete($id){

        $groupe = $this->groupe->find($id);

        return $groupe->delete();
    }
}
