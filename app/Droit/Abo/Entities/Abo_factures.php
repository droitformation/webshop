<?php namespace App\Droit\Abo\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Abo_factures extends Model{

    use SoftDeletes;

    protected $table    = 'abo_factures';
    protected $dates    = ['payed_at'];
    protected $fillable = ['abo_user_id','product_id','payed_at'];

    public function getAboFactureAttribute()
    {
        $this->load('abonnement');
        $file = 'files/abos/facture/'.$this->product_id.'/facture_'.$this->product->reference.'-'.$this->abo_user_id.'_'.$this->id.'.pdf';

        if (\File::exists($file))
        {
            return $file;
        }

        return null;
    }

    public function abonnement()
    {
        return $this->belongsTo('App\Droit\Abo\Entities\Abo_users','abo_user_id')->withTrashed();
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