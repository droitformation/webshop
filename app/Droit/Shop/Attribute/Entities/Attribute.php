<?php namespace App\Droit\Shop\Attribute\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attribute extends Model{

    use SoftDeletes;

    protected $table = 'shop_attributes';

    protected $dates = ['deleted_at'];

    protected $fillable = ['title'];
    /**
     * Set timestamps off
     */
    public $timestamps = false;

    public function attributs()
    {
        return $this->belongsToMany('App\Droit\Shop\Product\Entities\Product', 'shop_product_attributes', 'attribute_id','product_id');
    }

}