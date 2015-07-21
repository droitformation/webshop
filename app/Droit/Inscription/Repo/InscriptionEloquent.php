<?php namespace App\Droit\Inscription\Repo;

use App\Droit\Inscription\Repo\InscriptionInterface;
use App\Droit\Inscription\Entities\Inscription as M;

class InscriptionEloquent implements InscriptionInterface{

    protected $inscription;

    public function __construct(M $inscription)
    {
        $this->inscription = $inscription;
    }

    public function getAll(){

        return $this->inscription->with(['price','colloque','user'])->get();
    }

    public function getByColloque($id){

        return $this->inscription->where('colloque_id','=',$id)->with(['price','colloque','user'])->get();
    }

    public function find($id){

        return $this->inscription->with(['price','colloque','user','options'])->find($id);
    }

    public function create(array $data){

        $inscription = $this->inscription->create(array(
            'colloque_id'     => $data['colloque_id'],
            'user_id'         => $data['user_id'],
            'group_id'        => $data['group_id'],
            'inscription_no'  => $data['inscription_no'],
            'price_id'        => $data['price_id'],
            'payed_at'        => $data['payed_at'],
            'send_at'         => $data['send_at'],
            'status'          => $data['status'],
            'created_at'      => \Carbon\Carbon::now(),
            'updated_at'      => \Carbon\Carbon::now()
        ));

        if( ! $inscription )
        {
            return false;
        }

        return $inscription;

    }

    public function update(array $data){

        $inscription = $this->inscription->findOrFail($data['id']);

        if( ! $inscription )
        {
            return false;
        }

        $inscription->fill($data);

        $inscription->save();

        return $inscription;
    }

    public function delete($id){

        $inscription = $this->inscription->find($id);

        return $inscription->delete();

    }
}
