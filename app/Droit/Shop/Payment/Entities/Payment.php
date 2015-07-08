<?php namespace App\Droit\Shop\Payment\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model{

    protected $table = 'shop_payments';

    protected $fillable = array('title','image');

}