<?php

namespace App\Droit\Shop\Order\Worker;

interface OrderWorkerInterface{

    public function make($shipping,$coupon);
    public function insertOrder($commande);
    public function productIdFromForm($commande);
    public function saveCart($commande);
    public function productIdFromCart();
    public function newOrderNumber();

}