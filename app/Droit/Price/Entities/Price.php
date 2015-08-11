<?php namespace App\Droit\Price\Entities;

use Illuminate\Database\Eloquent\Model;

class Price extends Model{

    protected $table = 'colloque_prices';

    protected $fillable = array('colloque_id','price','type','description','rang');

    public $timestamps = false;

    public function getPriceCentsAttribute()
    {
        $money = new \App\Droit\Shop\Product\Entities\Money;
        $price = $this->price / 100;

        return $money->format($price);
    }

}