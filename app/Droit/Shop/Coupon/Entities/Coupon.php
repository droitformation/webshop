<?php namespace App\Droit\Shop\Coupon\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model{

    protected $table = 'shop_coupons';

    protected $dates = ['expire_at'];

    protected $fillable = ['value','title','type','expire_at','global'];

    public function getCouponValueAttribute()
    {
        if($this->type == 'price' || $this->type == 'priceshipping')
        {
            return '-'.$this->value.' CHF';
        }

        if($this->type == 'shipping'){
            return '';
        }

        return $this->value.'%';
    }

    public function getValeurAttribute()
    {
        if($this->type == 'price' || $this->type == 'priceshipping') {
            return $this->value;
        }

        if($this->type == 'shipping'){
            return '';
        }

        return $this->value.'%';
    }

    public function getCouponTitleAttribute()
    {
        if($this->type == 'shipping')
        {
            return 'Frais de port offerts';
        }

        return 'Rabais: '.$this->title;
    }

    public function products()
    {
        return $this->belongsToMany('App\Droit\Shop\Product\Entities\Product', 'shop_coupon_product', 'coupon_id', 'product_id');
    }

    public function orders()
    {
        return $this->hasMany('App\Droit\Shop\Order\Entities\Order','coupon_id');
    }
}