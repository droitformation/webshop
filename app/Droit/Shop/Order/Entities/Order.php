<?php namespace App\Droit\Shop\Order\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model{

    use SoftDeletes;

    protected $table = 'shop_orders';

    protected $dates = ['deleted_at','payed_at'];

    protected $fillable = ['user_id', 'adresse_id', 'coupon_id', 'payement_id', 'order_no', 'amount', 'shipping_id', 'onetimeurl', 'comment','admin'];

    public function getPriceCentsAttribute()
    {
        $money = new \App\Droit\Shop\Product\Entities\Money;
        $price = $this->amount / 100;

        return $money->format($price);
    }

    public function getStatusCodeAttribute()
    {
        switch ($this->status) {
            case 'pending':
                $status = [ 'status' => 'En attente', 'color' => 'warning' ];
                break;
            case 'payed':
                $status = [ 'status' => 'Payé', 'color' => 'success' ];
                break;
            case 'cancelled':
                $status = [ 'status' => 'Annulé', 'color' => 'danger' ];
                break;
        }

        return $status;
    }

    public function getPriceTotalExplodeAttribute()
    {
        $money = new \App\Droit\Shop\Product\Entities\Money;
        $total = $this->amount + $this->shipping->price;
        $price = $total / 100;
        $price = $money->format($price);

        return explode('.',$price);
    }

    public function getFactureAttribute()
    {
        $facture = public_path('files/shop/factures/facture_'.$this->order_no.'.pdf');

        if (\File::exists($facture))
        {
            return 'files/shop/factures/facture_'.$this->order_no.'.pdf';
        }

        return false;
    }

    public function getTotalWithShippingAttribute()
    {
        // formatter
        $money = new \App\Droit\Shop\Product\Entities\Money;

        // Load relations
        $this->load('shipping');

        $total = $this->amount + $this->shipping->price;
        $price = $total / 100;

        return $money->format($price);

    }

    /**
     * Scope a query to only get orders with free products
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeIsfree($query,$isfree)
    {
        $pivot = $this->products()->getTable();

        if($isfree) $query->whereHas('products', function ($q) use ($pivot) {
            $q->where("{$pivot}.isFree", 1);
        });
    }

    public function scopeStatus($query, $status)
    {
        if ($status) $query->where('status','=',$status);
    }

    public function products()
    {
        return $this->belongsToMany('App\Droit\Shop\Product\Entities\Product', 'shop_order_products', 'order_id', 'product_id')->withTimestamps()->withPivot('isFree','rabais');
    }

    public function user()
    {
        return $this->belongsTo('App\Droit\User\Entities\User');
    }

    public function adresse()
    {
        return $this->belongsTo('App\Droit\Adresse\Entities\Adresse');
    }

    public function shipping()
    {
        return $this->belongsTo('App\Droit\Shop\Shipping\Entities\Shipping');
    }

    public function payement()
    {
        return $this->belongsTo('App\Droit\Shop\Payment\Entities\Payment');
    }

    public function coupon()
    {
        return $this->belongsTo('App\Droit\Shop\Coupon\Entities\Coupon');
    }
}