<?php namespace App\Droit\Abo\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Abo_rappels extends Model{

    use SoftDeletes;

    protected $table    = 'abo_rappels';
    protected $fillable = ['abo_facture_id'];

    public function facture()
    {
        return $this->belongsTo('App\Droit\Abo\Entities\Abo_factures','abo_facture_id');
    }

}