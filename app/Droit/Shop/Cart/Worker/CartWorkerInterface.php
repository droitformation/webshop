<?php namespace App\Droit\Shop\Cart\Worker;

 interface CartWorkerInterface{

     public function noShipping();
     public function setCoupon($coupon);
     public function setShipping();
     public function getShipping();
     public function getTotalWeight();
     public function removeFreeShippingCoupon();
     public function reset();
     public function applyCoupon();
     public function couponForProduct();
     public function couponGlobal();
     public function resetCartPrices();
     public function calculPriceWithCoupon($product_id);
     public function searchItem($id);
     public function totalCartWithShipping();
     public function totalShipping();
     public function totalCart();

 }