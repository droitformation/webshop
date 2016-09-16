<?php namespace App\Droit\Shop\Cart\Worker;

 interface CartWorkerInterface{

     public function noShipping();
     public function setCoupon($coupon);
     public function setShipping();
     public function getShipping();
     public function getCoupon();
     public function getTotalWeight();
     public function removeFreeShippingCoupon();
     public function reset();
     public function applyCoupon();
     public function couponForProduct($type);
     public function couponGlobal();
     public function resetCartPrices();
     public function calculPriceWithCoupon($product_id, $operand);
     public function searchItem($id);
     public function totalCartWithShipping();
     public function totalShipping();
     public function totalCart();
     public function countCart();
     public function orderAbo();
     public function orderShop();
     public function getAboData();
     public function removeById($instance,$id);
 }