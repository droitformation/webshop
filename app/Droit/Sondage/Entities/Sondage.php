<?php

namespace App\Droit\Sondage\Entities;

use Illuminate\Database\Eloquent\Model;

class Sondage extends Model{

    protected $table = 'sondages';
    protected $dates = ['valid_at'];
    protected $fillable = ['colloque_id','valid_at'];

    public function reponses()
    {
        return $this->hasMany('App\Droit\Sondage\Entities\Reponse','sondage_id', 'id');
    }

    public function questions()
    {
        return $this->belongsToMany('App\Droit\Sondage\Entities\Question', 'sondage_question_items', 'sondage_id', 'question_id')->orderBy('rang')->withPivot('rang');
    }

    public function colloque()
    {
        return $this->belongsTo('App\Droit\Colloque\Entities\Colloque');
    }
}