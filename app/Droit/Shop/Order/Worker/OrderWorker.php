<?php

namespace App\Droit\Shop\Order\Worker;

use App\Droit\Shop\Order\Repo\OrderInterface;
use App\Droit\Shop\Cart\Worker\CartWorker;
use App\Droit\User\Repo\UserInterface;

class OrderWorker{

    protected $order;
    protected $cart;
    protected $user;

    public function __construct(OrderInterface $order, CartWorker $cart, UserInterface $user)
    {
        $this->order  = $order;
        $this->cart   = $cart;
        $this->user   = $user;
    }

    public function prepareOrder($shipping,$coupon)
    {
        $user     = $this->user->find(\Auth::user()->id);
        $cart     = \Cart::content();

        $commande = [
            'user_id'     => $user->id,
            'order_no'    => $this->newOrderNumber(),
            'amount'      =>  \Cart::total() * 100,
            'coupon_id'   => ($coupon ? $coupon['id'] : null),
            'shipping_id' => $shipping->id
        ];

        // Order global
        $order = $this->order->create($commande);

        if(!$order)
        {
            \Log::error('Problème lors de la commande'. serialize($commande));

            throw new \App\Exceptions\OrderCreationException('Problème lors de la commande');
        }

        $keyed = $this->productIdFromCart();
        $order->products()->attach($keyed);

        \Cart::destroy();

        return $order;

    }

    public function productIdFromCart()
    {
        foreach(\Cart::content() as $product)
        {
            if($product->qty > 1)
            {
                for ($x = 0; $x < $product->qty; $x++)
                {
                    $ids[] = $product->id;
                }
            }
            else
            {
                $ids[] = $product->id;
            }
        }

        return $ids;
    }

    public function newOrderNumber(){

        $lastid = 1;
        $year   = date("Y");
        $last   = $this->order->maxOrder($year);

        if($last)
        {
            list($y, $lastid) = explode('-', $last->order_no);
            $newid = intval($lastid) + 1;
        }

        // Build order number
        $order_no  = str_pad($newid, 8, '0', STR_PAD_LEFT);
        $order_no  = $year.'-'.$order_no;

        return $order_no;
    }

}