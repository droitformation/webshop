<?php namespace App\Hub\Shop\Tax\Entities;

use Illuminate\Database\Eloquent\Model;

class Tax extends Model{

    protected $table = 'shop_taxes';

    protected $fillable = array('title','value','price','type');
    /**
     * Set timestamps off
     */
    public $timestamps = false;

}