<?php namespace App\Droit\Shop\Cart\Worker;

use App\Droit\Shop\Cart\Worker\CartWorkerInterface;
use App\Droit\Shop\Product\Repo\ProductInterface;
use App\Droit\Shop\Shipping\Repo\ShippingInterface;
use App\Droit\Shop\Coupon\Repo\CouponInterface;

 class CartWorker implements CartWorkerInterface{

     protected $money;
     protected $product;
     protected $coupon;
     protected $shipping;

     public $orderShipping;
     public $orderWeight;
     public $hasCoupon  = false;
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
             $this->hasCoupon = false;
             $this->applyCoupon();
             session()->forget('coupon');

             throw new \App\Exceptions\CouponException('Ce rabais n\'est pas valide');
         }

         $this->hasCoupon = $valide;

         session(['coupon.title' => $valide->title]);
         session(['coupon.value' => $valide->value]);
         session(['coupon.type'  => $valide->type]);
         session(['coupon.id'    => $valide->id]);

         return $this;
     }

     public function setShipping(){

         $weight = (\Session::has('noShipping') ? null : $this->orderWeight);

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

     public function removeFreeShippingCoupon()
     {
         session()->forget('noShipping');

         return $this;
     }

     public function reset()
     {
         $this->removeFreeShippingCoupon()->resetCartPrices();

         return $this;
     }

     public function applyCoupon()
     {
         if($this->hasCoupon)
         {
            if($this->hasCoupon->type == 'product')
            {
                $this->couponForProduct();
            }
            elseif($this->hasCoupon->type == 'shipping')
            {
                session(['noShipping' => 'noShipping']);
            }
            else
            {
                $this->couponGlobal();
            }
         }
     }

     public function couponForProduct()
     {
         if(isset($this->hasCoupon->products))
         {
             foreach($this->hasCoupon->products as $product_id)
             {
                 $rowId = $this->searchItem($product_id->id);

                 if(!empty($rowId))
                 {
                     $newprice = $this->calculPriceWithCoupon($product_id->id);

                     \Cart::update($rowId[0], array('price' => $newprice));
                 }
             }
         }
     }

     public function couponGlobal()
     {
         $cart = \Cart::content();

         foreach($cart as $item)
         {
             $newprice = $item->price - ($item->price * ($this->hasCoupon->value)/100);

             \Cart::update($item->rowid, array('price' => $newprice));
         }
     }

     public function resetCartPrices()
     {
         $cart = \Cart::content();

         foreach($cart as $item)
         {
             $product = $this->product->find($item->id);

             if($product)
             {
                 \Cart::update($item->rowid, array('price' => $product->price_cents));
             }
         }
     }

     public function calculPriceWithCoupon($product_id)
     {
         $product = $this->product->find($product_id);

         return $product->price_cents - ($product->price_cents * ($this->hasCoupon->value)/100);
     }

     public function searchItem($id)
     {
        return \Cart::search(array('id' => $id));
     }

     public function totalCartWithShipping()
     {
         $shipping = $this->getTotalWeight()->setShipping()->orderShipping;

         return \Cart::total() + $shipping->price_cents;
     }

     public function totalShipping()
     {
         $shipping = $this->getTotalWeight()->setShipping()->orderShipping;

         return $shipping->price_cents;
     }

     public function totalCart()
     {
         return \Cart::total();
     }
 }