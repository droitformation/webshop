<?php namespace App\Droit\Shop\Rappel\Entities;

use Illuminate\Database\Eloquent\Model;

class Rappel extends Model{

    protected $table = 'shop_rappels';
    protected $dates = ['payed_at'];
    protected $fillable = ['order_id'];

    public function getDocRappelAttribute()
    {
        $file = 'files/shop/rappels/rappel_'.$this->id.'_'.$this->order->order_no.'.pdf';

        if (\File::exists($file))
        {
            return $file;
        }

        return null;
    }

    public function getMontantPriceAttribute()
    {
        $money = new \App\Droit\Shop\Product\Entities\Money;

        $price = $this->montant / 100;

        return $money->format($price);
    }

    public function order()
    {
        return $this->belongsTo('App\Droit\Shop\Order\Entities\Order');
    }
}