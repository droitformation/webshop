<?php namespace App\Droit\Shop\Cart\Worker;

use App\Droit\Shop\Product\Repo\ProductInterface;
use App\Droit\Shop\Shipping\Repo\ShippingInterface;
use App\Droit\Shop\Coupon\Repo\CouponInterface;

 class CartWorker{

     protected $money;
     protected $product;
     protected $coupon;
     protected $shipping;

     public $orderShipping;
     public $orderWeight;
     public $hasCoupon;
     public $noShipping = false;

     public function __construct(ProductInterface $product, ShippingInterface $shipping, CouponInterface $coupon)
     {
         $this->product  = $product;
         $this->shipping = $shipping;
         $this->coupon   = $coupon;
         $this->money    = new \App\Droit\Shop\Product\Entities\Money;
     }

     public function noShipping()
     {
         $this->noShipping = true;

         return $this;
     }

     public function setCoupon($coupon){

         $valide = $this->coupon->findByTitle($coupon);

         if(!$valide)
         {
             throw new \App\Exceptions\CouponException('False coupon');
         }

         $this->hasCoupon = $valide;

         return $this;

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

     public function applyCoupon()
     {
        if($this->hasCoupon)
        {
            if($this->hasCoupon->product_id)
            {
                return $this->searchItem($this->hasCoupon->product_id);
            }
        }
     }

     public function searchItem($id){

        return \Cart::search(array('id' => $id));

     }

 }