<?php namespace App\Droit\Shop\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model{

    use SoftDeletes;

    protected $table = 'shop_products';

    protected $dates = ['deleted_at'];

    protected $fillable = ['title', 'teaser', 'image', 'description', 'weight','price', 'sku', 'is_downloadable','hidden','url','rang'];

    public function getReferenceAttribute()
    {
        $attribute = $this->attributs->first(function ($item, $key) {
            return $item->id == 3;
        });

        return $attribute ? $attribute->pivot->value : '';
    }

    public function getEditionAttribute()
    {
        $attribute = $this->attributs->first(function ($item, $key) {
            return $item->id == 4;
        });

        return $attribute ? $attribute->pivot->value : '';
    }

    public function getEditionCleanAttribute()
    {
        $attribute = $this->attributs->where('id',4);

        return !$attribute->isEmpty() ? str_replace('/','-' , $attribute->first()->pivot->value) : '';
    }

    public function getIsbnAttribute()
    {
        $attribute = $this->attributs->where('id',1);

        return !$attribute->isEmpty() ? $attribute->first()->pivot->value : '';
    }

    public function getPriceNormalAttribute()
    {
        $money = new \App\Droit\Shop\Product\Entities\Money;

        return $money->format($this->price / 100);
    }

    public function getPriceSpecialAttribute()
    {
        $money = new \App\Droit\Shop\Product\Entities\Money;

        if(isset($this->pivot) && $this->pivot->rabais)
        {
            $price = ($this->price - ($this->price * $this->pivot->rabais/100)) / 100;
            return '('.ceil($this->pivot->rabais).'%) '.$money->format($price);
        }

        if(isset($this->pivot) && $this->pivot->price)
        {
            return $money->format($this->pivot->price / 100);
        }

        return null;
    }

    public function getPriceCentsAttribute()
    {
        $money = new \App\Droit\Shop\Product\Entities\Money;

        $normal = (isset($this->pivot) && $this->pivot->price ? $this->pivot->price : $this->price);

        if(isset($this->pivot) && $this->pivot->rabais)
        {
            $price = ($normal - ($normal * $this->pivot->rabais/100)) / 100;
        }
        else
        {
            return (isset($this->pivot) && $this->pivot->isFree ? 0 : $money->format($normal / 100));
        }

        return $money->format($price);
    }

    /* search scopes query */

    public function scopeSearch($query, $search)
    {
        if($search && !empty($search))
        {
            foreach($search as $item => $value)
            {
                $name = str_replace('_id','',$item).'s';

                $query->whereHas($name, function ($query) use ($item,$value)
                {
                    $query->where($item, $value);
                });
            }
        }
    }

    public function scopeReject($query, $categories)
    {
        if(!empty($categories))
        {
            $query->whereHas('categories', function ($query) use ($categories)
            {
                foreach($categories as $categorie)
                {
                    $query->where('categorie_id', '!=' ,$categorie);
                }
            });
        }
    }

    public function scopeHidden($query, $hidden)
    {
        if ($hidden) $query->where('hidden','=',0);
    }

    public function scopeNbr($query,$nbr)
    {
        if ($nbr) $query->take($nbr);
    }

    public function scopeVisible($query,$visible)
    {
        if ($visible) $query->where('hidden','=',0);
    }

    public function categories()
    {
        return $this->belongsToMany('\App\Droit\Shop\Categorie\Entities\Categorie', 'shop_product_categories', 'product_id', 'categorie_id');
    }

    public function authors()
    {
        return $this->belongsToMany('\App\Droit\Shop\Author\Entities\Author', 'shop_product_authors', 'product_id', 'author_id')->orderBy('last_name', 'asc')->withPivot('sorting');
    }

    public function domains()
    {
        return $this->belongsToMany('App\Droit\Domain\Entities\Domain', 'shop_product_domains', 'product_id', 'domain_id')->withPivot('sorting')->orderBy('sorting', 'asc');
    }

    public function attributs()
    {
        return $this->belongsToMany('App\Droit\Shop\Attribute\Entities\Attribute', 'shop_product_attributes', 'product_id', 'attribute_id')
            ->withPivot('sorting','value','id')
            ->where('value','!=','')
            ->orderBy('sorting', 'asc');
    }

    public function orders()
    {
        return $this->belongsToMany('App\Droit\Shop\Order\Entities\Order', 'shop_order_products','product_id', 'order_id')->withPivot('isFree','rabais','price');
    }

    public function abos()
    {
        return $this->belongsToMany('App\Droit\Abo\Entities\Abo', 'abo_products','product_id', 'abo_id');
    }

    public function stocks()
    {
        return $this->hasMany('App\Droit\Shop\Stock\Entities\Stock');
    }
}