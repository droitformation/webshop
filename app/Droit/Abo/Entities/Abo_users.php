<?php namespace App\Droit\Abo\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Abo_users extends Model{

    use SoftDeletes;

    protected $table = 'abo_users';

    protected $fillable = ['abo_id','numero','exemplaires','adresse_id','tiers_id','user_id','tiers_user_id','price','reference','remarque','status','renouvellement'];

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

    public function getAboTiersUserAttribute()
    {
        if($this->tiers_id > 0){
            return isset($this->tiers_user) ? $this->tiers_user : false;
        }

        return true;
    }

    public function getAboUserAttribute()
    {
       return isset($this->realuser) ? $this->realuser : false;
    }

    public function getUserAdresseAttribute()
    {
        // Change to user
        if(isset($this->realuser)) {
            return !$this->realuser->primary_adresse->isEmpty() ? $this->realuser->primary_adresse->first() : null;
        }

        return isset($this->user) ? $this->user : null;
    }

    public function getUserFacturationAttribute()
    {
        // Change to user
        if(isset($this->tiers_user) && isset($this->tiers_user->adresse_contact)){
            return $this->tiers_user->adresse_contact;
        }

        // Fallback to adresse
        return isset($this->tiers) ? $this->tiers : $this->user_adresse;
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

    public function user()
    {
        return $this->belongsTo('App\Droit\Adresse\Entities\Adresse','adresse_id')->withTrashed();
    }

    public function realuser()
    {
        return $this->belongsTo('App\Droit\User\Entities\User','user_id')->withTrashed();
    }

    public function tiers()
    {
        return $this->belongsTo('App\Droit\Adresse\Entities\Adresse','tiers_id')->withTrashed();
    }

    public function tiers_user()
    {
        return $this->belongsTo('App\Droit\User\Entities\User','tiers_user_id')->withTrashed();
    }

    public function factures()
    {
        return $this->hasMany('App\Droit\Abo\Entities\Abo_factures','abo_user_id','id');
    }

}