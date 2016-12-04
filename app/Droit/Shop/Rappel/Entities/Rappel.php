<?php namespace App\Droit\Shop\Rappel\Entities;

use Illuminate\Database\Eloquent\Model;

class Rappel extends Model{

    protected $table = 'shop_rappels';

    protected $fillable = ['order_id','montant'];

    public function getDocRappelAttribute()
    {
        $file = 'files/shop/rappels/rappel_'.$this->id.'_'.$this->order->order_no.'.pdf';

        if (\File::exists($file))
        {
            return $file;
        }

        return null;
    }

    public function order()
    {
        return $this->belongsTo('App\Droit\Shop\Order\Entities\Order');
    }
}