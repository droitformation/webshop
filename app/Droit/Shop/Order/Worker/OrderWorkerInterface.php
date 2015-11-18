<?php

namespace App\Droit\Shop\Order\Worker;

interface OrderWorkerInterface{

    public function make($shipping,$coupon);
    public function insertOrder($commande);
    public function saveCart($commande);
    public function productIdFromCart();

}