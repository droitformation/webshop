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

    public function order()
    {
        return $this->order;
    }

    public function getAdresse()
    {
        if(isset($this->order->user))
        {
            $this->order->user->load('adresses');
            return $this->order->user->adresse_facturation;
        }

        if(isset($this->order->adresse))
        {
            return $this->order->adresse;
        }
        
        throw new \App\Exceptions\AdresseNotExistPrepareException('No adresse');
    }

    public function getProducts()
    {
        return $this->order->products->groupBy(function ($item, $key) {
            return $item->id.$item->pivot->price.$item->pivot->rabais.$item->pivot->isFree;
        });
    }

    public function getFilename($rappel = null)
    {
        if($rappel){
            return public_path('files/shop/rappels/rappel_'.$rappel->id.'_'.$this->order->order_no.'.pdf');
        }

        return public_path('files/shop/factures/facture_'.$this->order->order_no.'.pdf');
    }
}