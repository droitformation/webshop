<?php namespace App\Droit\Abo\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Abo_factures extends Model{

    use SoftDeletes;

    protected $table    = 'abo_factures';
    protected $dates    = ['payed_at','created_at'];
    protected $fillable = ['abo_user_id','product_id','payed_at','created_at'];

    public function getRappelListAttribute()
    {
        return $this->rappels->map(function ($item, $key) {
            return ['id' => $item->id ,'date' => 'Rappel '.$item->created_at->format('d/m/Y'), 'doc_rappel' => $item->doc_rappel];
        });
    }
    
    public function getDocFactureAttribute()
    {
        $file = 'files/abos/facture/'.$this->product_id.'/facture_'.$this->product->reference.'-'.$this->abo_user_id.'_'.$this->product_id.'.pdf';

        if (\File::exists($file)) {
            return $file;
        }

        return null;
    }

    public function getAboRefAttribute()
    {
        return $this->product->reference.'-'.$this->abonnement->numero.'-'.$this->product->edition;
    }

    public function getProdReferenceAttribute()
    {
        return $this->product->reference;
    }

    public function getProdEditionAttribute()
    {
        return $this->product->title;
    }

    public function getProdIdAttribute()
    {
        return $this->product->id;
    }

    public function abonnement()
    {
        return $this->belongsTo('App\Droit\Abo\Entities\Abo_users','abo_user_id');
    }

    public function product()
    {
        return $this->belongsTo('App\Droit\Shop\Product\Entities\Product');
    }

    public function rappels()
    {
        return $this->hasMany('App\Droit\Abo\Entities\Abo_rappels','abo_facture_id','id');
    }

}