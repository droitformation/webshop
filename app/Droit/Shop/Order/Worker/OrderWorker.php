<?php

namespace App\Shop\Order\OrderWorker;

use App\Shop\Order\Repo\OrderInterface;
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

    public function prepareOrder()
    {
        $user     = $this->user->find(\Auth::user()->id);
        $shipping = $this->cart->totalShipping();
        $total    = $this->cart->totalCartWithShipping();
        $coupon   = (\Session::has('coupon') ? \Session::get('coupon') : false);

        $cart = \Cart::content();

        // Order global

        $order = $this->order->create([
            'user_id'     => $user,
            'coupon_id'   => $data['coupon_id'],
            'shipping_id' => $data['shipping_id']
       ]);

        foreach($cart as $article)
        {

        }
    }

}