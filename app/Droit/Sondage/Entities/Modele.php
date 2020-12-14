<?php namespace App\Droit\Sondage\Entities;

use Illuminate\Database\Eloquent\Model as M;
use Illuminate\Database\Eloquent\SoftDeletes;

class Modele extends M{

    use SoftDeletes;

    protected $table    = 'models';
    protected $fillable = ['title','description','rang'];

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

    public function avis()
    {
        return $this->belongsToMany('App\Droit\Sondage\Entities\Avis', 'model_avis', 'model_id', 'avis_id')
            ->orderBy('rang')
            ->withPivot('rang');
    }
}