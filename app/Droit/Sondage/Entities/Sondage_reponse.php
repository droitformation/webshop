<?php

namespace App\Droit\Sondage\Entities;

use Illuminate\Database\Eloquent\Model;

class Sondage_reponse extends Model{

    protected $table = 'sondage_question_reponses';

    protected $fillable = ['reponse_id'.'question_id','reponse'];

    public $timestamps = false;
    
    public function question()
    {
        return $this->belongsTo('App\Droit\Sondage\Entities\Question');
    }
}