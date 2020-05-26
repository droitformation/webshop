<?php namespace App\Droit\Price\Entities;

use Illuminate\Database\Eloquent\Model;

class Price extends Model{

    protected $table = 'colloque_prices';

    protected $dates = ['end_at'];

    protected $fillable = ['colloque_id','price','type','description','rang','remarque','end_at','main'];

    public $timestamps = false;

    public function getPriceCentsAttribute()
    {
        $money = new \App\Droit\Shop\Product\Entities\Money;
        $price = $this->price / 100;

        return $money->format($price);
    }

    public function getOccurrenceListAttribute()
    {
          return $this->occurrences->mapWithKeys_v2(function ($occurrence) {
              return [$occurrence->id => [
                  'id'         => $occurrence->id,
                  'title'      => $occurrence->title,
                  'contrainte' => $occurrence->pivot->contrainte,// all, only
              ]];
          });
    }

    public function inscriptions()
    {
        return $this->hasMany('App\Droit\Inscription\Entities\Inscription');
    }

    public function occurrences()
    {
        return $this->belongsToMany('App\Droit\Occurrence\Entities\Occurrence','colloque_occurrence_prices','price_id','occurrence_id')->withPivot(['contrainte']);
    }
}