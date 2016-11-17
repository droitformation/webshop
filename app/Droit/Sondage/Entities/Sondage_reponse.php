<?php

namespace App\Droit\Sondage\Entities;

use Illuminate\Database\Eloquent\Model;

class Sondage_reponse extends Model{

    protected $table = 'sondage_avis_reponses';

    protected $fillable = ['reponse_id','avis_id','reponse'];

    public function getUserIdAttribute()
    {
        return $this->response->user_id;
    }
    
    public function avis()
    {
        return $this->belongsTo('App\Droit\Sondage\Entities\Avis');
    }

    public function response()
    {
        return $this->belongsTo('App\Droit\Sondage\Entities\Reponse','reponse_id');
    }
}