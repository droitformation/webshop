<?php namespace App\Droit\Sondage\Entities;

use Illuminate\Database\Eloquent\Model as M;
use Illuminate\Database\Eloquent\SoftDeletes;

class Modele extends M{

    use SoftDeletes;

    protected $table    = 'models';
    protected $fillable = ['title','description','rang'];

    public function avis()
    {
        return $this->belongsToMany('App\Droit\Sondage\Entities\Avis', 'model_avis', 'model_id', 'avis_id')
            ->orderBy('rang')
            ->withPivot('rang');
    }
}