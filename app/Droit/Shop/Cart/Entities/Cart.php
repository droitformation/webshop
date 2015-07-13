<?php namespace App\Droit\Shop\Cart\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cart extends Model{

    use SoftDeletes;

    protected $table = 'shop_carts';

    protected $dates = ['deleted_at'];

    protected $fillable = ['user_id', 'coupon_id', 'cart'];

    public function user()
    {
        return $this->belongsTo('App\Droit\User\Entities\User');
    }

    public function coupon()
    {
        return $this->belongsTo('App\Droit\Shop\Coupon\Entities\Coupon');
    }
}