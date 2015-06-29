<?php namespace App\Droit\Shop\Shipping\Entities;

use Illuminate\Database\Eloquent\Model;

class Shipping extends Model{

    protected $table = 'shop_shipping';

    protected $fillable = array('title','value','price','type');
    /**
     * Set timestamps off
     */
    public $timestamps = false;

    public function getPriceCentsAttribute()
    {
        $money = new \App\Droit\Shop\Product\Entities\Money;
        $price = $this->price / 100;

        return $money->format($price);
    }

}