<?php

namespace App\Droit\Shop\Order\Entities;

class OrderBox
{
    protected $data;
    protected $shipping;

    public $boxes = [];

    public function __construct($data)
    {
        $this->shipping  = \App::make('App\Droit\Shop\Shipping\Repo\ShippingInterface');

        $this->data = $data;
    }

    /*
     * Calculate shipping list models
     * */
    public function calculate($weight)
    {
        $this->boxes = $this->calculateShippingBoxes($weight);

        return $this;
    }

    /*
     * Get free shipping model
     * */
    public function getFreeShipping()
    {
        return $this->shipping->getShipping(null);
    }

    public function getShippingList()
    {
        return collect($this->boxes)->groupBy('id')->map(function ($item, $key) {
            return ['shipping_id' => $key, 'qty' => $item->count()];
        });
    }

    public function getTotalShippingPrice()
    {
        // Format price correctly
        $money       = new \App\Droit\Shop\Product\Entities\Money;
        $price_total = collect($this->boxes)->sum('price');
        $price       = $price_total / 100;

        return $money->format($price);
    }

    public function getListBoxes(){

        return collect($this->boxes)->groupBy(function ($item, $key) {
            return ($item->value/1000).' Kg | '.$item->price_cents;
        })->map(function ($item, $key) {
            return $item->count();
        });
    }

    /*
     * Return array with shipping models
     * */
    public function calculateShippingBoxes($weight, $paquets = []){

        $shipping = $this->shipping->getShipping($weight);

        if($weight > 0){
            $paquets[] = $shipping;
            $newweight = $weight - $shipping->value;
            $paquets   = array_merge($this->calculateShippingBoxes($newweight, $paquets));
        }

        return $paquets;
    }
}