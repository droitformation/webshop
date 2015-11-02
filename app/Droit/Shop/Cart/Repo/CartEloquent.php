<?php namespace App\Droit\Shop\Cart\Repo;

use App\Droit\Shop\Cart\Repo\CartInterface;
use App\Droit\Shop\Cart\Entities\Cart as M;

class CartEloquent implements CartInterface{

    protected $cart;

    public function __construct(M $cart)
    {
        $this->cart = $cart;
    }

    public function getAll(){

        return $this->cart->all();
    }

    public function find($id){

        return $this->cart->with(['user','cart'])->find($id);
    }

    public function create(array $data){

        $cart = $this->cart->create(array(
            'user_id'   => $data['user_id'],
            'coupon_id' => $data['coupon_id'],
            'cart'      => $data['cart'],
        ));

        if( ! $cart )
        {
            return false;
        }

        return $cart;

    }

    public function update(array $data){

        $cart = $this->cart->findOrFail($data['id']);

        if( ! $cart )
        {
            return false;
        }

        $cart->fill($data);

        $cart->save();

        return $cart;
    }

    public function delete($id)
    {
        return $this->cart->find($id)->delete();
    }

}
