<?php namespace App\Droit\Abo\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Abo_rappels extends Model{

    use SoftDeletes;

    protected $table    = 'abo_rappels';
    protected $fillable = ['abo_facture_id'];

    public function getAboRappelAttribute()
    {
        $this->load('facture','facture.abonnement');

        $file = 'files/abos/rappel/'.$this->facture->product->id.'/rappel_'.$this->facture->product->reference.'-'.$this->facture->abo_user_id.'_'.$this->facture->id.'.pdf';

        if (\File::exists(public_path($file)))
        {
            return $file;
        }

        return null;
    }

    public function facture()
    {
        return $this->belongsTo('App\Droit\Abo\Entities\Abo_factures','abo_facture_id');
    }

}