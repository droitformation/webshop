<?php namespace App\Droit\Shop\Cart\Worker;

use App\Droit\Shop\Product\Repo\ProductInterface;
use App\Droit\Shop\Shipping\Repo\ShippingInterface;

 class CartWorker{

     protected $money;
     protected $product;
     protected $shipping;

     public $orderShipping;
     public $orderWeight;
     public $coupon;
     public $noShipping = false;

     public function __construct(ProductInterface $product, ShippingInterface $shipping)
     {
         $this->product  = $product;
         $this->shipping = $shipping;
         $this->money    = new \App\Droit\Shop\Product\Entities\Money;
     }

     public function noShipping()
     {
         $this->noShipping = true;

         return $this;
     }

     public function setCoupon($coupon){


     }

     public function setShipping(){

         $weight = ($this->noShipping ? null : $this->orderWeight);

         $this->orderShipping = $this->shipping->getShipping($weight);

         return $this;
     }

     public function getShipping(){

         $shipping = $this->shipping->getShipping($this->orderWeight);

         return $shipping;
     }

     public function getTotalWeight(){

         $totalWeight = 0;
         // Get current cart instance
         $cart     = \Cart::content();
         $products = $cart->lists('options');

         if(!$products->isEmpty())
         {
             foreach($products as $product)
             {
                 $totalWeight +=  $product->weight;
             }
         }

         $this->orderWeight = $totalWeight;

         return $this;
     }

 }