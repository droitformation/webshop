<?php namespace App\Droit\Shop\Coupon\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model{

    protected $table = 'shop_coupons';

    protected $dates = ['expire_at'];

    protected $fillable = array('value','title','type','expire_at','product_id');

}