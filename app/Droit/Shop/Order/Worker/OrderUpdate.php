<?php

namespace App\Droit\Shop\Order\Worker;

class OrderUpdate
{
    public $data;
    protected $order;
    protected $request;

    protected $repo;

    public function __construct($request,$order){
        $this->request = $request;
        $this->order   = $order;

        $this->repo = \App::make('App\Droit\Shop\Order\Repo\OrderInterface');
    }

    public function prepareData()
    {
        $this->data = array_filter(array_only($this->request,['id','created_at','paquet','user_id','adresse_id','comment']));

        /*
            'amount'      =>
            'coupon_id'   => ,
            'shipping'    => ,
            'payement_id' => ,
            'products'    =>
        */
    }
    // coupon
    // shipping paquets
    // messages
    // pdf
}