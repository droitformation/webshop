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

    public function showCoupon()
    {
        // if it is a global coupon
        if($this->order->coupon->global == 1){
            // if the admin makes it (maybe later) allow for coupon expiration
            // Or the coupon is valid
            if(
                ($this->order->admin == 1) && ($this->order->coupon->expire_at < date('Y-m-d')) ||
                ($this->order->coupon->expire_at >= date('Y-m-d'))
            )
            {
                // test if products are contained in coupon
                return !empty(array_intersect($this->order->coupon->products->pluck('id')->all(), $this->order->products->pluck('id')->all()));
            }
            else{
                return false;
            }
        }

        // it is another kind of coupon
        return true;
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
        
        throw new \App\Exceptions\AdresseNotExistException('No adresse');
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

    public function getReferences()
    {
        return $this->order->references;
    }
}