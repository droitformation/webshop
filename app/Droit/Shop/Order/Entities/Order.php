<?php namespace App\Hub\Shop\Order\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model{

    use SoftDeletes;

    protected $table = 'shop_orders';

    protected $dates = ['deleted_at'];

    protected $fillable = ['user_id', 'coupon_id', 'shipping_id', 'onetimeurl'];

    public function products()
    {
        return $this->belongsToMany('App\Droit\Shop\Product\Entities\Product', 'shop_order_products', 'product_id', 'order_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Droit\User\Entities\User');
    }

    public function shipping()
    {
        return $this->hasOne('App\Droit\Shop\Shipping\Entities\Shipping');
    }

    public function coupon()
    {
        return $this->hasOne('App\Hub\Shop\Coupon\Entities\Coupon');
    }
}