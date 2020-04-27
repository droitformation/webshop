<?php namespace App\Droit\Abo\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Abo_users extends Model{

    use SoftDeletes;

    protected $table = 'abo_users';
    protected $dates = ['deleted_at'];
    protected $fillable = ['abo_id','numero','exemplaires','adresse_id','tiers_id','user_id','raison',
        'tiers_user_id','price','reference', 'reference_id', 'remarque','status','renouvellement','deleted_at'];

    public function getMainStatusAttribute()
    {
        return $this->is_tiers ? 'tiers' : $this->status;
    }

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
       return $this->user;
    }

    public function getUserAdresseAttribute()
    {
        // Change to user
        if(isset($this->user) && isset($this->user->primary_adresse)) {
            return $this->user->primary_adresse;
        }

        return null;
    }

    public function getUserFacturationAttribute()
    {
        // If exist tiers facturation => tiers contact
        if(isset($this->user->tiers_adresse)){
            return $this->user->tiers_adresse;
        }

        // if not tiers facture => user facturation OR contact, User model gives facturation if exist else primary adresse
        if(isset($this->user->adresse_contact)){
            return $this->user->adresse_contact;
        }

        return null;
    }

    public function getIsTiersAttribute()
    {
        return isset($this->user->tiers_adresse) && ($this->user->tiers_adresse->id != $this->user->adresse_contact->id) ? true : false;
    }

    public function getSubstituteEmailAttribute()
    {
        if(substr(strrchr($this->user_facturation->email, "@"), 1) == 'publications-droit.ch'){
            return true;
        }

        return false;
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

        $product = $this->abo->current_product;
        $price   = ($this->price && !empty($this->price) ? $this->price : $product->price);

        $price = $price / 100;
        return $price > 0 ? $money->format($price) : '';
    }

    public function getRemiseAttribute()
    {
        $money = new \App\Droit\Shop\Product\Entities\Money;

        if($this->price && $this->price > 0){
            $price = $this->price / 100;
            return $price > 0 ? $money->format($price) : '';
        }

        return null;
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

            return $remise > 0 ? $money->format($remise) : null;
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

/*    public function user()
    {
        return $this->belongsTo('App\Droit\Adresse\Entities\Adresse','adresse_id')->withTrashed();
    }*/

    public function user()
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

    public function references()
    {
        return $this->belongsTo('App\Droit\Transaction\Entities\Transaction_reference','reference_id');
    }
}