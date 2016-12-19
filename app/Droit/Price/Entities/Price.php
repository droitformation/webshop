<?php namespace App\Droit\Price\Entities;

use Illuminate\Database\Eloquent\Model;

class Price extends Model{

    protected $table = 'colloque_prices';

    protected $fillable = ['colloque_id','price','type','description','rang','remarque'];

    public $timestamps = false;

    public function getPriceCentsAttribute()
    {
        $money = new \App\Droit\Shop\Product\Entities\Money;
        $price = $this->price / 100;

        return $money->format($price);
    }

    public function inscriptions()
    {
        return $this->hasMany('App\Droit\Inscription\Entities\Inscription');
    }

}