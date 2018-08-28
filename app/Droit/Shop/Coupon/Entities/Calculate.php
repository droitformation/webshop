<?php namespace App\Droit\Shop\Coupon\Entities;


class Calculate
{
    protected $product;
    protected $coupon;

    public function __construct($product,$coupon)
    {
        $this->product = $product;
        $this->coupon = $coupon;
    }

    public function calculate()
    {
        $money = new \App\Droit\Shop\Product\Entities\Money();

        if($this->coupon->type == 'global' || $this->coupon->type == 'product') {
            $price = $this->product->price_normal - ($this->product->price_normal * ($this->coupon->value)/100);
        }

        if($this->coupon->type == 'price' || $this->coupon->type == 'priceshipping') {
            $price = $this->product->price_normal - $this->coupon->value;
        }

        return $money->format($price);
    }

    public function global()
    {
        return ($this->coupon->global == 1) && ( $this->coupon->expire_at >= date('Y-m-d') ) ? $this->calculate() : null;
    }

    public function contains()
    {

    }
}