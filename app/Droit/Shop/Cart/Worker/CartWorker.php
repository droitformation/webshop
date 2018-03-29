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
      * Get coupon title
      * @return float
      * */
     public function getCoupon()
     {
         $coupon = (\Session::has('coupon') ? \Session::get('coupon') : false);

         if($coupon)
         {
             if($coupon['type'] == 'shipping')
             {
                 return '<p><span class="text-muted">Frais de port offerts</span></p>';
             }

             return '<p><span class="text-muted">'.$coupon['title'].'</span> &nbsp;'.$coupon['value'].'%</p>';
         }

         return null;
     }

     /**
      * Get total from weight
      * IIT
      * @return void
      * */
     public function getTotalWeight(){

         $totalWeight = 0;
         // Get current cart instance
         $cart     = \Cart::instance('shop')->content();
         $products = $cart->pluck('options');

         if(!$products->isEmpty())
         {
             foreach($products as $product)
             {
                 $totalWeight +=  isset($product->weight) && !empty($product->weight) ? $product->weight : 0;
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
             if($this->hasCoupon->type == 'product' || $this->hasCoupon->type == 'price' || $this->hasCoupon->type == 'priceshipping')
             {
                 $this->couponForProduct($this->hasCoupon->type);
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
     public function couponForProduct($type)
     {
         if(isset($this->hasCoupon->products))
         {
             foreach($this->hasCoupon->products as $product)
             {
                 // search if product eligible for discount is in cart
                 $rowId = $this->searchItem($product->id);
        
                 if(!$rowId->isEmpty())
                 {
                     $rowId = $rowId->first();

                     if($type == 'product')
                     {
                         $newprice = $this->calculPriceWithCoupon($product,'percent');
                     }
                     elseif($type == 'price' || $type = 'priceshipping')
                     {
                         $newprice = $this->calculPriceWithCoupon($product, 'minus');

                         if($type = 'priceshipping')
                         {
                             session(['noShipping' => 'noShipping']);
                         }
                     }

                     \Cart::instance('shop')->update($rowId->rowId, array('price' => $newprice));
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
         $cart = \Cart::instance('shop')->content();

         foreach($cart as $item)
         {
             $newprice = $item->price - ($item->price * ($this->hasCoupon->value)/100);

             \Cart::instance('shop')->update($item->rowId, array('price' => $newprice));
         }
     }

     /**
     * Reset all prices to original from products
     * IIT
     * @return void
     * */
     public function resetCartPrices()
     {
         $cart = \Cart::instance('shop')->content();

         foreach($cart as $item)
         {
             $product = $this->product->find($item->id);

             if($product)
             {
                 \Cart::instance('shop')->update($item->rowId, array('price' => $product->price_cents));
             }
         }
     }

     /**
      * Calculat price from product and apply coupon discount
      * IIT
      * @return float
      * */
     public function calculPriceWithCoupon($product,$operand)
     {
         if($operand == 'percent')
         {
             return $product->price_cents - ($product->price_cents * ($this->hasCoupon->value)/100);
         }

         if($operand == 'minus')
         {
             return $product->price_cents - $this->hasCoupon->value;
         }
     }

     /**
      *  Search in cart
      * @return boolean
     */
     public function searchItem($id)
     {
         return \Cart::instance('shop')->search(function ($cartItem, $rowId) use ($id) {
             return $cartItem->id == (int)$id;
         });
     }

     /**
     * Calculat price with shipping
     * IIT through
     * @return float
     * */
     public function totalCartWithShipping()
     {
         $shipping = $this->totalShipping();
         $total    = $this->totalCart() + $shipping;
         
         return \number_format((float)$total, 2, '.', '');
     }

     /**
     * Get price of shipping
     * IIT through
     * @return float
     * */
     public function totalShipping()
     {
         $shipping = $this->getTotalWeight()->setShipping()->orderShipping;

         // Add shipping for abos
         if(!\Cart::instance('abonnement')->content()->isEmpty() && !\Session::has('noShipping'))
         {
             $abo_shipping = \Cart::instance('abonnement')->content()->map(function ($item, $key) {
                 return $item->options->shipping_cents;
             })->sum();

             return \number_format((float)($abo_shipping + $shipping->price_cents), 2, '.', '');
         }

         return $shipping->price_cents;
     }

     /**
     * Get total cart price
     * @return float
     * */
     public function totalCart()
     {
         $shop_cart = \Cart::instance('shop')->total();
         $abo_cart  = \Cart::instance('abonnement')->total();

         return number_format((float) ($shop_cart + $abo_cart), 2, '.', '');
     }

     /**
      * Get total count items in cart
      * @return float
      * */
     public function countCart()
     {
         $shop_cart = \Cart::instance('shop')->count();
         $abo_cart  = \Cart::instance('abonnement')->count();

         return $shop_cart + $abo_cart;
     }

     /**
      * Do we want an abo
      * @return float
      * */
     public function orderShop()
     {
         $abo = \Cart::instance('shop')->count();

         return $abo > 0 ? true : false;
     }

     /**
      * Do we want an abo
      * @return float
      * */
     public function orderAbo()
     {
         $abo = \Cart::instance('abonnement')->count();

         return $abo > 0 ? true : false;
     }

     /**
      * Data for an abo
      * @return float
      * */
     public function getAboData()
     {
         $order = \Cart::instance('abonnement')->content();
         $user  = \Auth::user()->load('adresses');

         $adresse_id = $user->adresse_livraison->id;

         return $order->map(function ($item, $key) use ($adresse_id,$user) {
             return [
                 'abo_id'         => $item->id,
                 'product_id'     => $item->options->product_id,
                 'exemplaires'    => 1,
                 'adresse_id'     => null,
                 'user_id'        => $user->id,
                 'status'         => 'abonne',
                 'renouvellement' => 'auto'
             ];
         });
     }

     public function removeById($instance,$id)
     {
         $toRemove = \Cart::instance($instance)->search(function ($cartItem, $id) {
             return $cartItem->id === $id;
         });

         if(!$toRemove->isEmpty())
         {
             foreach($toRemove as $remove)
             {
                 \Cart::instance($instance)->remove($remove);
             }
         }
     }
 }