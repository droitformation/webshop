<?php namespace App\Droit\Abo\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Abo extends Model{

    use SoftDeletes;

    protected $table = 'abos';

    protected $fillable = ['title','plan','logo','name'];

    public function getPlanFrAttribute()
    {
        $traduction = ['year' => 'Annuel', 'semester' => 'Semestriel', 'month' => 'Mensuel'];

        return $traduction[$this->plan];
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

    public function products()
    {
        return $this->belongsToMany('App\Droit\Shop\Product\Entities\Product', 'abo_products', 'abo_id','product_id');
    }

    public function abonnements()
    {
        return $this->hasMany('App\Droit\Abo\Entities\Abo_users','abo_id');
    }
}