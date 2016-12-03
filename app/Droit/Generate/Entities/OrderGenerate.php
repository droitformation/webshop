<?php

namespace App\Droit\Generate\Entities;

use App\Droit\Shop\Order\Entities\Order;

class OrderGenerate
{
    protected $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function getAdresse()
    {
        if($this->order->user_id)
        {
            $this->order->user->load('adresses');
            return $this->order->user->adresse_facturation;
        }
      
        return $this->order->adresse;
    }

    public function getProducts()
    {
        return $this->order->products->groupBy(function ($item, $key) {
            return $item->id.$item->pivot->price.$item->pivot->rabais.$item->pivot->isFree;
        });
    }

    public function getName($rappel = null)
    {
        if($rappel){
            return public_path('files/shop/rappels/rappel_'.$rappel->id.'_'.$this->order->order_no.'.pdf');
        }

        return public_path('files/shop/factures/facture_'.$this->order->order_no.'.pdf');
    }
}