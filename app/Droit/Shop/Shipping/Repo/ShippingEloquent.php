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

        if($type)
        {
            return $this->shipping->with(['orders'])->where('type','=',$type)->get();
        }

        return $this->shipping->with(['orders'])->orderByRaw("-hidden",'ASC')->get();
    }

    public function getShipping($weight = null)
    {
        if($weight)
        {
            $weight = $weight > 30000 ? 30000 : $weight;

             return $this->shipping
                ->select('shop_shipping.*',\DB::raw('CAST( value as UNSIGNED ) as weight'))
                ->where('shop_shipping.type','=','poids')
                ->where('shop_shipping.value','>=',$weight)
                ->whereNull('shop_shipping.hidden')
                ->orderBy('weight', 'asc')
                ->take(1)->get()->first();
        }

        return $this->shipping->where('type','=','gratuit')->take(1)->get()->first();

    }

    public function find($id){

        return $this->shipping->find($id);
    }

    public function create(array $data){

        $shipping = $this->shipping->create(array(
            'title' => $data['title'],
            'value' => $data['value'],
            'price' => $data['price'] * 100,
            'type'  => $data['type']
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
        $shipping->price = $data['price'] * 100;

        $shipping->save();

        return $shipping;
    }

    public function delete($id){

        $shipping = $this->shipping->find($id);

        return $shipping->delete();
    }

}
