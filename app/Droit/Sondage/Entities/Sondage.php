<?php

namespace App\Droit\Sondage\Entities;

use Illuminate\Database\Eloquent\Model;

class Sondage extends Model{

    protected $table = 'sondages';
    protected $dates = ['valid_at'];
    protected $fillable = ['colloque_id','valid_at','description','marketing','title','image','signature','organisateur','email'];

    public function getAvisVueAttribute()
    {
        return $this->avis->map(function ($row, $key) {
            $sort = preg_replace('/[^a-z]/i', '', trim(strip_tags($row->question)));
            $row->setAttribute('alpha',strtolower($sort));
            $row->setAttribute('rang',$row->pivot->rang);
            $row->setAttribute('class',null);
            $row->setAttribute('choices_list',$row->choices ? explode(',', $row->choices) : null);
            $row->setAttribute('type_name',$row->type_name);
            $row->setAttribute('question_simple',strip_tags($row->question));
            return $row;
        })->sortBy('rang')->values();
    }

    public function reponses()
    {
        return $this->hasMany('App\Droit\Sondage\Entities\Reponse','sondage_id', 'id');
    }

    public function reponses_no_test()
    {
        return $this->hasMany('App\Droit\Sondage\Entities\Reponse','sondage_id', 'id')->whereNull('isTest');
    }

    public function avis()
    {
        return $this->belongsToMany('App\Droit\Sondage\Entities\Avis', 'sondage_avis_items', 'sondage_id', 'avis_id')->orderBy('rang')->withPivot('rang');
    }

    public function colloque()
    {
        return $this->belongsTo('App\Droit\Colloque\Entities\Colloque');
    }

    public function liste()
    {
        return $this->hasOne('App\Droit\Newsletter\Entities\Newsletter_lists', 'colloque_id', 'colloque_id');
    }

}