<?php namespace App\Droit\Shop\Cart\Worker;

 class CartWorker{

     protected $money;

     function __construct() {

         $this->money = new \App\Droit\Shop\Product\Entities\Money;
     }

     public function getShipping(){

         // Get current cart instance
         $cart = Cart::content();
     }

 }