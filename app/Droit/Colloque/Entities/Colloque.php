<?php

namespace App\Droit\Colloque\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Colloque extends Model
{

    use SoftDeletes;

    protected $table = 'colloques';

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'titre', 'soustitre', 'sujet', 'remarques', 'start_at', 'end_at', 'registration_at', 'active_at', 'organisateur_id',
        'location_id', 'compte_id', 'visible', 'bon', 'facture', 'created_at', 'updated_at'
    ];

    public function getIllustrationAttribute()
    {
        $this->load('documents');

        $illustration = $this->documents->filter(function ($item){
            return $item->type == 'illustration';
        });

        if(!$illustration->isEmpty())
        {
            return 'files/colloques/illustrations/'.$illustration->first()->path;
        }
        else
        {
            return 'files/colloques/illustrations/illu.png';
        }

    }

    public function location()
    {
        return $this->belongsTo('App\Droit\Location\Entities\Location');
    }

    public function organisateur()
    {
        return $this->belongsTo('App\Droit\Organisateur\Entities\Organisateur');
    }

    public function centres()
    {
        return $this->belongsToMany('App\Droit\Organisateur\Entities\Organisateur','colloque_organisateurs','colloque_id','organisateur_id');
    }

    public function compte()
    {
        return $this->belongsTo('App\Droit\Compte\Entities\Compte');
    }

    public function prices()
    {
        return $this->hasMany('App\Droit\Price\Entities\Price');
    }

    public function documents()
    {
        return $this->hasMany('App\Droit\Document\Entities\Document');
    }

    public function options()
    {
        return $this->hasMany('App\Droit\Option\Entities\Option');
    }

}
