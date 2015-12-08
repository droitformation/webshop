<?php namespace App\Droit\Shop\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model{

    use SoftDeletes;

    protected $table = 'shop_products';

    protected $dates = ['deleted_at'];

    protected $fillable = ['title', 'teaser', 'image', 'description', 'weight','price', 'sku', 'is_downloadable'];

    public function getReferenceAttribute()
    {
        $this->load('attributes');
        $attribute = $this->attributes->where('id',4);

        return !$attribute->isEmpty() ? $attribute->first()->pivot->value : '';
    }

    public function getEditionAttribute()
    {
        $this->load('attributes');
        $attribute = $this->attributes->where('id',3);

        return !$attribute->isEmpty() ? $attribute->first()->pivot->value : '';
    }

    public function getPriceCentsAttribute()
    {

        $money = new \App\Droit\Shop\Product\Entities\Money;

        if(isset($this->pivot) && $this->pivot->rabais)
        {
            $price = ($this->price - ($this->price * $this->pivot->rabais/100)) / 100;
        }
        else
        {
            $price = $this->price / 100;
        }

        return $money->format($price);
    }

    public function categories()
    {
        return $this->belongsToMany('\App\Droit\Shop\Categorie\Entities\Categorie', 'shop_product_categories', 'product_id', 'categorie_id');
    }

    public function authors()
    {
        return $this->belongsToMany('\App\Droit\Author\Entities\Author', 'shop_product_authors', 'product_id', 'author_id')->withPivot('sorting')->orderBy('sorting', 'asc');
    }

    public function domains()
    {
        return $this->belongsToMany('App\Droit\Domain\Entities\Domain', 'shop_product_domains', 'product_id', 'domain_id')->withPivot('sorting')->orderBy('sorting', 'asc');
    }

    public function attributes()
    {
        return $this->belongsToMany('App\Droit\Shop\Attribute\Entities\Attribute', 'shop_product_attributes', 'product_id', 'attribute_id')->withPivot('sorting','value','id')->orderBy('sorting', 'asc');
    }

    public function orders()
    {
        return $this->belongsToMany('App\Droit\Shop\Order\Entities\Order', 'shop_order_products','product_id', 'order_id')->withPivot('isFree','rabais');
    }

    public function abos()
    {
        return $this->belongsToMany('App\Droit\Abo\Entities\Abo', 'abo_products','product_id', 'abo_id');
    }

}