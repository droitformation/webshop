<?php namespace App\Droit\Abo\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Abo_rappels extends Model{

    use SoftDeletes;

    protected $table    = 'abo_rappels';
    protected $fillable = ['abo_facture_id'];

    public function getAboRappelAttribute()
    {
        $this->load('facture');

        $file = 'files/abos/'.$this->facture->abonnement->abo_edition.'/rappel_'.$this->facture->abonnement->abo_ref.'_'.$this->facture->id.'.pdf';

        if (\File::exists($file))
        {
            return $file;
        }

        return false;
    }

    public function facture()
    {
        return $this->belongsTo('App\Droit\Abo\Entities\Abo_factures','abo_facture_id');
    }

}