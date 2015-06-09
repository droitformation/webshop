<?php namespace App\Droit\Shipping\Repo;

use App\Droit\Shop\Shipping\Repo\ShippingInterface;
use App\Droit\Shop\Shipping\Entities as M;

class ShippingEloquent implements ShippingInterface{

    protected $shipping;

    public function __construct(M $shipping)
    {
        $this->shipping = $shipping;
    }

    public function getAll(){

        return $this->shipping->all();
    }

    public function find($id){

        return $this->shipping->find($id);
    }

    public function create(array $data){

        $shipping = $this->shipping->create(array(
            'title' => $data['title'],
            'value' => $data['value'],
            'price' => $data['price'],
            'type' => $data['type']
        ));

        if( ! $shipping )
        {
            return false;
        }

        return $shipping;

    }

    public function update(array $data){

        $shipping = $this->shipping->findOrFail($data['id']);

        if( ! $shipping )
        {
            return false;
        }

        $shipping->fill($data);

        $shipping->save();

        return $shipping;
    }

    public function delete($id){

        $shipping = $this->shipping->find($id);

        return $shipping->delete();

    }

}
