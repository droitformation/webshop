<?php namespace App\Droit\Abo\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Abo extends Model{

    use SoftDeletes;

    protected $table = 'abos';

    protected $fillable = ['title','plan','logo','email','name','compte','adresse','price','shipping','remarque'];

    public function getPlanFrAttribute()
    {
        $traduction = ['year' => 'Annuel', 'semester' => 'Semestriel', 'month' => 'Mensuel'];

        return $traduction[$this->plan];
    }

    public function getLogoFileAttribute()
    {
        return $this->logo ? $this->logo : 'unine.png';
    }

    public function getPriceCentsAttribute()
    {
        $money = new \App\Droit\Shop\Product\Entities\Money;
        $price = $this->price / 100;

        return $money->format($price);
    }

    public function getShippingCentsAttribute()
    {
        $money = new \App\Droit\Shop\Product\Entities\Money;
        $price = $this->shipping / 100;

        return $money->format($price);
    }

    public function getCurrentProductAttribute()
    {
        $this->load('products');

        if(!$this->products->isEmpty())
        {
            $products = $this->products->sortByDesc('created_at');

            return $products->first();
        }

        return factory(\App\Droit\Shop\Product\Entities\Product::class)->make(['image' => 'placeholder.jpg', 'title' => $this->title , 'id' => null]);
    }

    public function getFrontendProductAttribute()
    {
        $this->load('active_products');

        if(!$this->active_products->isEmpty())
        {
            $products = $this->active_products->sortByDesc('created_at');

            return $products->first();
        }

        return factory(\App\Droit\Shop\Product\Entities\Product::class)->make(['image' => 'placeholder.jpg', 'title' => $this->title , 'id' => null]);
    }

    public function products()
    {
        return $this->belongsToMany('App\Droit\Shop\Product\Entities\Product', 'abo_products', 'abo_id','product_id')
            ->orderBy('created_at','desc');
    }

    public function active_products()
    {
        return $this->belongsToMany('App\Droit\Shop\Product\Entities\Product', 'abo_products', 'abo_id','product_id')
            ->where('hidden','=',0)->orWhereNull('hidden')
            ->orderBy('created_at','desc');
    }

    public function abonnements()
    {
        return $this->hasMany('App\Droit\Abo\Entities\Abo_users','abo_id')->has('user');
    }

    public function resilie()
    {
        return $this->hasMany('App\Droit\Abo\Entities\Abo_users','abo_id')->onlyTrashed();
    }

}