<?php

namespace App\Droit\Shop\Order\Entities;

use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    protected $table = 'shop_order_products';

    protected $fillable = ['order_id', 'product_id','isFree','rabais'];

    public function products()
    {
        return $this->hasMany('App\Droit\Shop\Product\Entities\Product');
    }

}
