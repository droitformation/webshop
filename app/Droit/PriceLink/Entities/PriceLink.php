<?php namespace App\Droit\PriceLink\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PriceLink extends Model{

    use SoftDeletes;

    protected $table = 'price_link';
    protected $fillable = ['price','type','description','rang','remarque'];

    public function getPriceCentsAttribute()
    {
        $money = new \App\Droit\Shop\Product\Entities\Money;
        $price = $this->price / 100;

        return $money->format($price);
    }

    public function colloques()
    {
        return $this->belongsToMany('App\Droit\Colloque\Entities\Colloque','price_link_colloques','price_link_id','colloque_id');
    }

    public function inscriptions()
    {
        return $this->hasMany('App\Droit\Inscription\Entities\Inscription', 'price_link_id');
    }
}