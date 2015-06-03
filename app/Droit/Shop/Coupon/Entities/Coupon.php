<?php namespace App\Hub\Shop\Coupon\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model{

    use SoftDeletes;

    protected $table = 'shop_coupons';

    protected $dates = ['deleted_at','expire_at'];

    protected $fillable = array('value', 'title', 'expire_at');

}