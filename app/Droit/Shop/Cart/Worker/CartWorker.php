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

         return $this;

     }

     public function setShipping(){

         $weight = (session()->has('noShipping') ? null : $this->orderWeight);

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
        $cart = \Cart::content();

        if($this->hasCoupon)
        {
            if($this->hasCoupon->type == 'product')
            {
                $rowId = $this->searchItem($this->hasCoupon->product_id);
                session()->forget('noShipping');
                if(!empty($rowId))
                {
                    $newprice = $this->calculPriceWithCoupon();
                    \Cart::update($rowId[0], array('price' => $newprice));
                }
                else
                {
                    throw new \App\Exceptions\CouponException('Ce rabais n\'est pas valide pour ce produit');
                }
            }
            elseif($this->hasCoupon->type == 'shipping')
            {
                session(['noShipping' => 'noShipping']);
            }
            else
            {
                session()->forget('noShipping');
                foreach($cart as $item)
                {
                    $newprice = $item->price - ($item->price * ($this->hasCoupon->value)/100);

                    \Cart::update($item->rowid, array('price' => $newprice));
                }
            }
        }
        else
        {
            $this->resetCartPrices();
        }

     }

     public function resetCartPrices()
     {
         $cart = \Cart::content();

         foreach($cart as $item)
         {
             $product = $this->product->find($item->id);
             \Cart::update($item->rowid, array('price' => $product->price_cents));
         }
     }

     public function calculPriceWithCoupon()
     {
         $product = $this->product->find($this->hasCoupon->product_id);

         $newprice = $product->price_cents - ($product->price_cents * ($this->hasCoupon->value)/100);

         return number_format((float)$newprice, 2, '.', '');
     }

     public function searchItem($id)
     {
        return \Cart::search(array('id' => $id));
     }

     public function totalCartWithShipping()
     {
         $shipping = $this->getTotalWeight()->setShipping()->orderShipping;

         $price = \Cart::total() + $shipping->price_cents;

         return number_format((float)$price, 2, '.', '');
     }

     public function totalShipping()
     {
         $shipping = $this->getTotalWeight()->setShipping()->orderShipping;

         return number_format((float)$shipping->price_cents, 2, '.', '');
     }
 }