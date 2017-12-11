<?php namespace App\Droit\Shop\Shipping\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Paquet extends Model{

    use SoftDeletes;

    protected $table = 'shop_paquets';

    protected $dates = ['deleted_at'];

    protected $fillable = ['shipping_id', 'qty','remarque'];

    public function shipping()
    {
        return $this->belongsTo('App\Droit\Shop\Shipping\Entities\Shipping');
    }

}