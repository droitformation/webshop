<?php

namespace App\Droit\Sondage\Entities;

use Illuminate\Database\Eloquent\Model;

class Reponse extends Model{

    protected $table = 'sondage_reponses';

    protected $fillable = ['sondage_id','user_id','email','sent_at','response_at'];

    public function items()
    {
        return $this->hasMany('App\Droit\Sondage\Entities\Sondage_reponse', 'reponse_id', 'id');
    }
    
    public function sondage()
    {
        return $this->belongsTo('App\Droit\Sondage\Entities\Sondage');
    }

    public function user()
    {
        return $this->belongsTo('App\Droit\User\Entities\User');
    }
}