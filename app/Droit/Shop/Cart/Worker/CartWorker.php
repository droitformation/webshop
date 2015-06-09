<?php namespace App\Droit\Shop\Cart\Worker;

use App\Droit\Shop\Product\Repo\ProductInterface;

 class CartWorker{

     protected $money;
     protected $product;

     public function __construct(ProductInterface $product)
     {
         $this->product = $product;
         $this->money   = new \App\Droit\Shop\Product\Entities\Money;
     }

     public function getShipping(){

         $totalWeight = 0;
         // Get current cart instance
         $cart     = \Cart::content();
         $products = $cart->lists('options');

         if(!$products->isEmpty()){
             foreach($products as $product){
                 $totalWeight +=  $product->weight;
             }
         }

         return $totalWeight;
     }

 }