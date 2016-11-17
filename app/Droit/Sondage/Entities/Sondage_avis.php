<?php

namespace App\Droit\Sondage\Entities;

use Illuminate\Database\Eloquent\Model;

class Sondage_avis extends Model{

    protected $table = 'sondage_avis_items';

    protected $fillable = ['sondage_id','avis_id','rang'];

    public $timestamps = false;

    public function avis()
    {
        return $this->belongsTo('App\Droit\Sondage\Entities\Avis');
    }

    public function sondage()
    {
        return $this->belongsTo('App\Droit\Sondage\Entities\Sondage');
    }
    
}