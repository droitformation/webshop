<?php namespace App\Droit\Shop\Shipping\Repo;

use App\Droit\Shop\Shipping\Repo\PaquetInterface;
use App\Droit\Shop\Shipping\Entities\Paquet as M;

class PaquetEloquent implements PaquetInterface{

    protected $paquet;

    public function __construct(M $paquet)
    {
        $this->paquet = $paquet;
    }

    public function getAll(){

        return $this->paquet->all();
    }

    public function find($id){

        return $this->paquet->find($id);
    }

    public function create(array $data){

        $paquet = $this->paquet->create(array(
            'qty'         => $data['qty'],
            'shipping_id' => $data['shipping_id'],
            'remarque'    => $data['remarque']
        ));

        if( ! $paquet )
        {
            return false;
        }

        return $paquet;

    }

    public function update(array $data){

        $paquet = $this->paquet->findOrFail($data['id']);

        if( ! $paquet )
        {
            return false;
        }

        $paquet->fill($data);

        $paquet->save();

        return $paquet;
    }

    public function delete($id){

        $paquet = $this->paquet->find($id);

        return $paquet->delete();
    }

}
