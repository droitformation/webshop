<?php namespace App\Droit\Shop\Order\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model{

    use SoftDeletes;

    protected $table = 'shop_orders';

    protected $dates = ['deleted_at','payed_at','send_at'];

    protected $fillable = ['user_id', 'adresse_id', 'coupon_id', 'status','payement_id', 'reference_id', 'order_no', 'amount', 'shipping_id', 'paquet','onetimeurl', 'comment','admin','send_at','payed_at'];

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

    public function getWeightAttribute()
    {
        return $this->products->reduce(function ($carry, $product) {
            return $carry +$product->weight;
        }, 0);
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
        $price = explode('.',$this->total_with_shipping);
        $francs   = isset($price[0]) ? $price[0] : 0;
        $centimes = isset($price[1]) ? $price[1] : 0;

        return [$francs,$centimes];
    }

    public function getAmountExplodeAttribute()
    {
        $float = number_format($this->amount/100, 2);
        $price = explode('.',$float);
        $francs   = isset($price[0]) ? $price[0] : 0;
        $centimes = isset($price[1]) ? rtrim($price[1],0) : 0;

        return [$francs,$centimes];
    }

    public function getFactureAttribute()
    {
        $facture = public_path('files/shop/factures/facture_'.$this->order_no.'.pdf');

        if (\File::exists($facture)) {
            return 'files/shop/factures/facture_'.$this->order_no.'.pdf';
        }

        return false;
    }

    public function getTotalWithShippingAttribute()
    {
        // formatter
        $money = new \App\Droit\Shop\Product\Entities\Money;

        // Load relations
        $this->load('shipping','paquets');

        // safe guard
        if(!isset($this->shipping) && $this->paquets->isEmpty()){
            return 0;
        }

        $shipping_price = 0;

        // simple shipping
        if(isset($this->shipping)){
            $paquet = isset($this->paquet) ? $this->paquet : 1 ;
            $shipping_price = $this->shipping->price > 0 ? $this->shipping->price * $paquet : 0;
        }

        // Shipping x nbr paquets
        $price = !$this->paquets->isEmpty() ? $this->paquets->reduce(function ($carry, $item) {
            return $carry + ($item->shipping->price * $item->qty);
        }) : $shipping_price;

        $total = $this->amount + $price;
        $price = $total / 100;

        return $money->format($price);
    }

    public function getTotalSumAttribute()
    {
        // Load relations
        $this->load('shipping');

        // safe guard
        if(!isset($this->shipping) && $this->paquets->isEmpty()){
            return 0;
        }

        $price = !$this->paquets->isEmpty() ? $this->paquets->reduce(function ($carry, $item) {
            return $carry + ($item->shipping->price * $item->qty);
        }) : $this->shipping->price;

        $total = $this->amount + $price;
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
        $this->load(['shipping','coupon','paquets']);

        if(isset($this->coupon) && $this->coupon->type == 'shipping') {
            return 0;
        }

        $shipping_price = 0;

        // simple shipping
        if(isset($this->shipping)){
            $paquet = isset($this->paquet) ? $this->paquet : 1 ;
            $shipping_price = $this->shipping->price > 0 ? $this->shipping->price * $paquet : 0;
        }

        if(isset($this->shipping) || !$this->paquets->isEmpty()) {

            $price = !$this->paquets->isEmpty() ? $this->paquets->reduce(function ($carry, $item) {
                return $carry + ($item->shipping->price * $item->qty);
            }) : $shipping_price;

            return $money->format($price/100);
        }
        else{
            // safe guard
            return 0;
        }
    }

    public function getShowCouponAttribute()
    {
        // if it is a global coupon
        if($this->coupon->global == 1){
            // if the admin makes it (maybe later) allow for coupon expiration
            // Or the coupon is valid
            if(
                ($this->admin == 1) && ($this->coupon->expire_at < date('Y-m-d')) ||
                ($this->coupon->expire_at >= date('Y-m-d'))
            )
            {
                // test if products are contained in coupon
                return !empty(array_intersect($this->coupon->products->pluck('id')->all(), $this->products->pluck('id')->all()));
            }
            else{
                return false;
            }
        }

        // it is another kind of coupon
        return true;
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

    public function paquets()
    {
        return $this->belongsToMany('App\Droit\Shop\Shipping\Entities\Paquet','shop_order_paquets','order_id','paquet_id');
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

    public function references()
    {
        return $this->belongsTo('App\Droit\Transaction\Entities\Transaction_reference','reference_id');
    }
}