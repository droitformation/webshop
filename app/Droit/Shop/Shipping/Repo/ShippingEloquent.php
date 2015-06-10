<?php namespace App\Droit\Shop\Shipping\Repo;

use App\Droit\Shop\Shipping\Repo\ShippingInterface;
use App\Droit\Shop\Shipping\Entities\Shipping as M;

class ShippingEloquent implements ShippingInterface{

    protected $shipping;

    public function __construct(M $shipping)
    {
        $this->shipping = $shipping;
    }

    public function getAll($type = null){

        return $this->shipping->where('type','=',$type)->get();
    }

    public function getShipping($weight = null){

        if($weight)
        {
            return $this->shipping
                ->select('shop_shipping.*',\DB::raw('CAST( value as UNSIGNED ) as weight'))
                ->where('type','=','poids')->where('value','>',$weight)->orderBy('weight', 'asc')->skip(0)->take(1)->get();
        }

        return $this->shipping->where('type','=','gratuit')->take(1)->get();

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
