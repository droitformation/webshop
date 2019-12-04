<?php namespace App\Droit\Shop\Stock\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stock extends Model{

    protected $table = 'shop_stocks';

    protected $fillable = ['product_id','amount','motif','operator','created_at'];

    public function product()
    {
        return $this->belongsTo('App\Droit\Shop\Product\Entities\Product');
    }
}