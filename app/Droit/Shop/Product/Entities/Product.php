<?php namespace App\Droit\Shop\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model{

    use SoftDeletes;

    protected $table = 'shop_products';

    protected $dates = ['deleted_at'];

    protected $fillable = array('title', 'teaser', 'image', 'description', 'weight','price', 'sku', 'is_downloadable');

    public function getPriceCentsAttribute()
    {
        $money = new \App\Droit\Shop\Product\Entities\Money;
        $price = $this->price / 100;

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
        return $this->belongsToMany('App\Droit\Shop\Attribute\Entities\Attribute', 'shop_product_attributes', 'product_id', 'attribute_id')->withPivot('sorting','value')->orderBy('sorting', 'asc');
    }


    public function attri()
    {
        return $this->belongsToMany('App\Droit\Shop\Attribute\Entities\Attribute', 'shop_product_attributes', 'product_id', 'attribute_id')->withPivot('sorting','value')->orderBy('sorting', 'asc');
    }

/*    public function price()
    {
        return $this->hasOne('App\Droit\Shop\Product\Entities\Price','product_id', 'id');
    }*/
}