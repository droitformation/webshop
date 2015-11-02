<?php namespace App\Droit\Shop\Coupon\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model{

    protected $table = 'shop_coupons';

    protected $dates = ['expire_at'];

    protected $fillable = array('value','title','type','expire_at');

    public function products()
    {
        return $this->belongsToMany('App\Droit\Shop\Product\Entities\Product', 'shop_coupon_product', 'coupon_id', 'product_id');
    }

    public function orders()
    {
        return $this->hasMany('App\Droit\Shop\Order\Entities\Order','coupon_id');
    }
}