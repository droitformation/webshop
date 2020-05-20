<?php

namespace App\Droit\Sondage\Entities;

use Illuminate\Database\Eloquent\Model;

class Sondage extends Model{

    protected $table = 'sondages';
    protected $dates = ['valid_at'];
    protected $fillable = ['colloque_id','valid_at','description','marketing','title','image','signature','organisateur','email'];

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