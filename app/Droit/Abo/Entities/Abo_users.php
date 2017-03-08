<?php namespace App\Droit\Abo\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Abo_users extends Model{

    use SoftDeletes;

    protected $table = 'abo_users';

    protected $fillable = ['abo_id','numero','exemplaires','adresse_id','tiers_id','price','reference','remarque','status','renouvellement'];

    public function getAboNoAttribute()
    {
        $this->load('abo');

        $product = $this->abo->current_product;

        return $product->reference.'-'.$this->numero.'-'.$product->edition;
    }

    public function getAboRefAttribute()
    {
        $this->load('abo');

        $product = $this->abo->current_product;

        return $product->reference.'-'.$this->numero;
    }

    public function getAboEditionAttribute()
    {
        $this->load('abo');

        $product = $this->abo->current_product;

        return $product->reference;
    }

    public function getAboProductAttribute()
    {
        $this->load('abo');

        $product = $this->abo->current_product;

        return $product->id;
    }

    public function getPriceTotalExplodeAttribute()
    {
        return explode('.',$this->price_cents);
    }

    public function getUserAdresseAttribute()
    {
        $user = isset($this->originaluser) && !isset($this->user) ? $this->originaluser : $this->user;
        
        return isset($user) ? $user : null;
    }

    public function getUserFacturationAttribute()
    {
        $user = isset($this->tiers) ? $this->tiers : $this->user;

        return isset($user) ? $user : null;
    }

    public function getPriceCentsAttribute()
    {
        $money = new \App\Droit\Shop\Product\Entities\Money;
        $this->load('abo');

        $product = $this->abo->current_product;
        $price   = ($this->price && !empty($this->price) ? $this->price : $product->price);
        $total   = $price * $this->exemplaires;

        if($this->abo->shipping){
            $total += $this->abo->shipping;
        }

        $price = $total / 100;

        return $money->format($price);
    }

    public function getPriceRemiseAttribute()
    {
        $money = new \App\Droit\Shop\Product\Entities\Money;

        if($this->price && !empty($this->price))
        {
            $price   = $this->price / 100;
            return $money->format($price);
        }

        return "";
    }

    public function getPriceCentsRemiseAttribute()
    {
        $money = new \App\Droit\Shop\Product\Entities\Money;
        $this->load('abo');

        $product = $this->abo->current_product;

        if($this->price && !empty($this->price))
        {
            $remise = $product->price - $this->price;
            $remise = $remise * $this->exemplaires;
            $remise = $remise / 100;

            return $money->format($remise);
        }

        return null;
    }

    public function getShippingCentsAttribute()
    {
        $money = new \App\Droit\Shop\Product\Entities\Money;
        $this->load('abo');

        $shipping = $this->abo->shipping / 100;

        return $money->format($shipping);
    }

    public function abo()
    {
        return $this->belongsTo('App\Droit\Abo\Entities\Abo','abo_id');
    }

    public function originaluser()
    {
        return $this->belongsTo('App\Droit\Adresse\Entities\Adresse','adresse_id','old_id')->withTrashed();
    }

    public function user()
    {
        return $this->belongsTo('App\Droit\Adresse\Entities\Adresse','adresse_id')->withTrashed();
    }

    public function originaltiers()
    {
        return $this->belongsTo('App\Droit\Adresse\Entities\Adresse','tiers_id','old_id')->withTrashed();
    }

    public function tiers()
    {
        return $this->belongsTo('App\Droit\Adresse\Entities\Adresse','tiers_id')->withTrashed();
    }

    public function factures()
    {
        return $this->hasMany('App\Droit\Abo\Entities\Abo_factures','abo_user_id','id');
    }

}