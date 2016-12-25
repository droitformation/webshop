<?php namespace App\Droit\Price\Entities;

use Illuminate\Database\Eloquent\Model;

class Price extends Model{

    protected $table = 'colloque_prices';

    protected $fillable = ['colloque_id','price','type','description','rang','remarque'];

    public $timestamps = false;

    public function getPriceCentsAttribute()
    {
        $money = new \App\Droit\Shop\Product\Entities\Money;
        $price = $this->price / 100;

        return $money->format($price);
    }

    public function getOccurrenceListAttribute()
    {
        /*   
          return [
              'list'   => $this->occurrences->pluck('id')->all(),
              'titles' => $this->occurrences->pluck('title')->all(),
            */
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