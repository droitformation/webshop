<?php namespace App\Droit\Abo\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Abo_factures extends Model{

    use SoftDeletes;

    protected $table    = 'abo_factures';
    protected $dates    = ['payed_at'];
    protected $fillable = ['abo_user_id','product_id','payed_at'];

    public function abonnement()
    {
        return $this->belongsTo('App\Droit\Abo\Entities\Abo_user','abo_user_id');
    }

    public function rappels()
    {
        return $this->hasMany('App\Droit\Abo\Entities\Abo_rappels','abo_facture_id','id');
    }

}