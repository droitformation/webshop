<?php

namespace App\Droit\Sondage\Entities;

use Illuminate\Database\Eloquent\Model;

class Sondage_question extends Model{

    protected $table = 'sondage_question_items';

    protected $fillable = ['sondage_id','question_id']; 

    public $timestamps = false;

    public function question()
    {
        return $this->belongsTo('App\Droit\Sondage\Entities\Question');
    }

    public function sondage()
    {
        return $this->belongsTo('App\Droit\Sondage\Entities\Sondage');
    }
    
}