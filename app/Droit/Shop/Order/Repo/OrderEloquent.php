<?php namespace App\Shop\Order\Repo;

use App\Shop\Order\Repo\OrderInterface;
use App\Shop\Order\Entities as M;

class OrderEloquent implements OrderInterface{

    protected $order;

    public function __construct(M $order)
    {
        $this->order = $order;
    }

    public function getAll(){

        return $this->order->all();
    }

    public function find($id){

        return $this->order->with(['products','user','coupon','shipping'])->find($id);
    }

    public function create(array $data){

        $order = $this->order->create(array(
            'user_id'     => $data['user_id'],
            'coupon_id'   => $data['coupon_id'],
            'shipping_id' => $data['shipping_id']
        ));

        if( ! $order )
        {
            return false;
        }

        return $order;
    }

    public function update(array $data){

        $order = $this->order->findOrFail($data['id']);

        if( ! $order )
        {
            return false;
        }

        $order->fill($data);

        $order->save();

        return $order;
    }

    public function delete($id){

        $order = $this->order->find($id);

        return $order->delete();
    }

}
