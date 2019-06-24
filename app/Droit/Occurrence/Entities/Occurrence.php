<?php namespace App\Droit\Occurrence\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Occurrence extends Model{

    use SoftDeletes;

    protected $table = 'colloque_occurrences';

    protected $dates = ['deleted_at','starting_at'];

    protected $fillable = ['colloque_id','title','lieux_id','starting_at','full','capacite_salle'];

    public function getIsActiveAttribute()
    {
        return $this->starting_at >= \Carbon\Carbon::today() ? true : false;
    }

    public function getIsOpenAttribute()
    {
        if(!$this->capacite_salle){
            return true;
        }

        return $this->capacite_salle > $this->inscriptions->count() ? true : false;
    }

    public function colloque()
    {
        return $this->belongsTo('App\Droit\Colloque\Entities\Colloque');
    }

    public function location()
    {
        return $this->belongsTo('App\Droit\Location\Entities\Location','lieux_id');
    }

    public function prices()
    {
        return $this->belongsToMany('App\Droit\Price\Entities\Price','colloque_occurrence_prices','occurrence_id','price_id')->withPivot(['contrainte']);
    }

    public function inscriptions()
    {
        return $this->belongsToMany('App\Droit\Inscription\Entities\Inscription','colloque_occurrence_users','occurrence_id','inscription_id');
    }
}