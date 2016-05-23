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

     /**
      * Set a coupon and put in session
      * IIT
      * 
      * @param \App\Droit\Shop\Coupon\Entities\Coupon
      * @return void
      * @throws \App\Exceptions\CouponException if coupon is not valid
      * */
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

     /**
     * Set shipping
     * We can set a free shipping coupon else get shipping with weight
     * IIT
     * @return void
     * */
     public function setShipping()
     {
         $weight = (\Session::has('noShipping') ? null : $this->orderWeight);

         $this->orderShipping = $this->shipping->getShipping($weight);

         return $this;
     }

     /**
      * Get shipping from weight
      * IIT
      * @return \App\Droit\Shop\Shipping\Entities\Shipping
      * */
     public function getShipping()
     {
         $shipping = $this->shipping->getShipping($this->orderWeight);

         return $shipping;
     }

     /**
      * Get total from weight
      * IIT
      * @return void
      * */
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

     /**
      * Forget free coupon
      * @return void
      * */
     public function removeFreeShippingCoupon()
     {
         session()->forget('noShipping');

         return $this;
     }

     /**
      * Reset cart prices to normal
      * @return void
      * */
     public function reset()
     {
         $this->removeFreeShippingCoupon()->resetCartPrices();

         return $this;
     }

     /**
      * Apply coupon and calculate new price for products in cart
      * IIT
      * @return void
      * */
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

    /**
    * Calculate new price for products and update cart
    * IIT
    * @return void
    * */
     public function couponForProduct()
     {
         if(isset($this->hasCoupon->products))
         {
             foreach($this->hasCoupon->products as $product)
             {
                 // search if product eligible for discount is in cart
                 $rowId = $this->searchItem($product->id);

                 if(!empty($rowId))
                 {
                     $newprice = $this->calculPriceWithCoupon($product);

                     \Cart::update($rowId[0], array('price' => $newprice));
                 }
             }
         }
     }

     /**
     * Calculate new price for all products and update cart
     * IIT
     * @return void
     * */
     public function couponGlobal()
     {
         $cart = \Cart::content();

         foreach($cart as $item)
         {
             $newprice = $item->price - ($item->price * ($this->hasCoupon->value)/100);

             \Cart::update($item->rowid, array('price' => $newprice));
         }
     }

     /**
     * Reset all prices to original from products
     * IIT
     * @return void
     * */
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

     /**
     * Calculat price from product and apply coupon discount
     * IIT
     * @return float
     * */
     public function calculPriceWithCoupon($product)
     {
         return $product->price_cents - ($product->price_cents * ($this->hasCoupon->value)/100);
     }

     /**
      *  Search in cart
      * @return boolean
     */
     public function searchItem($id)
     {
        return \Cart::search(['id' => $id]);
     }

     /**
     * Calculat price with shipping
     * IIT through
     * @return float
     * */
     public function totalCartWithShipping()
     {
         $shipping = $this->getTotalWeight()->setShipping()->orderShipping;

         return \Cart::total() + $shipping->price_cents;
     }

     /**
     * Get price of shipping
     * IIT through
     * @return float
     * */
     public function totalShipping()
     {
         $shipping = $this->getTotalWeight()->setShipping()->orderShipping;

         return $shipping->price_cents;
     }

     /**
     * Get total cart price
     * @return float
     * */
     public function totalCart()
     {
         return \Cart::total();
     }
 }