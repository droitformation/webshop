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

        // Order global
/*        $order = $this->order->create([
            'user_id'     => $user,
            'coupon_id'   => ($coupon ? $coupon->id : null),
            'shipping_id' => $shipping->id
        ]);*/

        $order = [
            'user_id'     => $user->id,
            'coupon_id'   => ($coupon ? $coupon['id'] : null),
            'shipping_id' => $shipping->id
        ];

        echo '<pre>';
        print_r($order);
        echo '</pre>';exit;

        foreach($cart as $article)
        {

        }
    }

}