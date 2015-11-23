<?php namespace App\Droit\Abo\Repo;

use App\Droit\Abo\Repo\AboInterface;
use App\Droit\Abo\Entities\Abo as M;

class AboEloquent implements AboInterface{

    protected $abo;

    public function __construct(M $abo)
    {
        $this->abo = $abo;
    }

    public function getAll(){

        return $this->abo->all();
    }

    public function find($id){

        return $this->abo->find($id);
    }

    public function create(array $data){

        $abo = $this->abo->create(array(
            'title'      => $data['title'],
            'product_id' => $data['product_id'],
            'plan'       => $data['plan']
        ));

        if( ! $abo )
        {
            return false;
        }

        return $abo;

    }

    public function update(array $data){

        $abo = $this->abo->findOrFail($data['id']);

        if( ! $abo )
        {
            return false;
        }

        $abo->fill($data);

        $abo->save();

        return $abo;
    }

    public function delete($id){

        $abo = $this->abo->find($id);

        return $abo->delete();

    }
}
