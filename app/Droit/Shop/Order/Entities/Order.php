<?php namespace App\Droit\Shop\Order\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model{

    use SoftDeletes;

    protected $table = 'shop_orders';

    protected $dates = ['deleted_at','payed_at','send_at'];

    protected $fillable = ['user_id', 'adresse_id', 'coupon_id', 'status','payement_id', 'order_no', 'amount', 'shipping_id', 'paquet','onetimeurl', 'comment','admin','send_at','payed_at'];

    public function getPriceCentsAttribute()
    {
        $money = new \App\Droit\Shop\Product\Entities\Money;
        $price = $this->amount / 100;

        return $money->format($price);
    }

    public function getRappelListAttribute()
    {
        return $this->rappels->map(function ($item, $key) {
            return ['id' => $item->id ,'date' => 'Rappel '.$item->created_at->format('d/m/Y'), 'doc_rappel' => $item->doc_rappel];
        });
    }

    public function getOrderAdresseAttribute()
    {
        if(isset($this->user))
        {
            return $this->user->adresses->where('type',1)->first();
        }

        if(isset($this->adresse))
        {
            return $this->adresse;
        }
        
        return null;
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

    public function getStatusTitleAttribute()
    {
        if($this->total_with_shipping > 0){
            return $this->payed_at ? 'Payé' : 'En Attente';
        }

        return 'Gratuit';
    }

    public function getPriceTotalExplodeAttribute()
    {
        $money = new \App\Droit\Shop\Product\Entities\Money;

        // Shipping x nbr paquets
        $price = $this->paquet ? ($this->shipping->price * $this->paquet) : $this->shipping->price;

        $total = $this->amount + $price;
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

        // Shipping x nbr paquets
        $price = $this->paquet ? ($this->shipping->price * $this->paquet) : $this->shipping->price;

        $total = $this->amount + $price;
        $price = $total / 100;

        return $money->format($price);
    }

    public function getTotalSumAttribute()
    {
        // Load relations
        $this->load('shipping');

        $total = $this->amount + $this->shipping->price;
        $price = $total / 100;

        // Calcul with TVA
        $totalTVA = ($price*(2.5/100)) + $price;
        $totalTVA = str_replace(',', '.', $totalTVA);

        return $totalTVA;
    }

    public function getTotalShippingAttribute()
    {
        // formatter
        $money = new \App\Droit\Shop\Product\Entities\Money;

        // Load relations
        $this->load('shipping','coupon');

        if(count($this->coupon) && $this->coupon->type == 'shipping')
        {
            return 0;
        }

        if(isset($this->shipping))
        {
            $price = $this->paquet ? ($this->shipping->price * $this->paquet) : $this->shipping->price;

            return $money->format($price/100);
        }
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
    
    public function scopeSend($query, $send)
    {
        if($send)
        {
            $get = ($send == 'send') ? 'whereNotNull' : 'whereNull';
            $query->$get('send_at');
        }
    }

    public function scopePeriod($query, $period)
    {
        if ($period) {
            $start = \Carbon\Carbon::parse($period['start'])->startOfDay();
            $end   = \Carbon\Carbon::parse($period['end'])->endOfDay();

            $query->whereBetween('created_at', [$start, $end]);
        };
    }

    public function scopeSearch($query, $order_no)
    {
        if ($order_no) $query->where('order_no','=',$order_no);
    }

    public function products()
    {
        return $this->belongsToMany('App\Droit\Shop\Product\Entities\Product', 'shop_order_products', 'order_id', 'product_id')
            ->withTimestamps()
            ->withPivot('isFree','rabais','price')
            ->orderBy('title');
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

    public function rappels()
    {
        return $this->hasMany('App\Droit\Shop\Rappel\Entities\Rappel');
    }
}