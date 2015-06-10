<?php namespace App\Droit\Shop\Cart\Worker;

use App\Droit\Shop\Product\Repo\ProductInterface;
use App\Droit\Shop\Shipping\Repo\ShippingInterface;

 class CartWorker{

     protected $money;
     protected $product;
     protected $shipping;

     public $orderShipping;
     public $orderWeight;

     public function __construct(ProductInterface $product, ShippingInterface $shipping)
     {
         $this->product  = $product;
         $this->shipping = $shipping;
         $this->money    = new \App\Droit\Shop\Product\Entities\Money;
     }

     public function setShipping(){

         $weight = (!session()->has('noshipping') ? $this->orderWeight : null);

         $this->orderShipping = $this->shipping->getShipping($weight);

         return $this;
     }

     public function calculateShippingRates(){

         $shipping = $this->shipping->getAll();

     }

     public function getShipping(){

         $collection = $this->shipping->getAll('poids');
         $sorted     = $collection->sortBy('value');

         $weight = $this->orderWeight;
         $weight = 5000;
         $collection->search(function ($item, $weight) {
             return $weight > $item->value;
         });

         return $sorted->toArray();
     }

     public function getTotalWeight(){

         $totalWeight = 0;
         // Get current cart instance
         $cart     = \Cart::content();
         $products = $cart->lists('options');

         if(!$products->isEmpty()){
             foreach($products as $product){
                 $totalWeight +=  $product->weight;
             }
         }

         $this->orderWeight = $totalWeight;

         return $this;
     }

 }