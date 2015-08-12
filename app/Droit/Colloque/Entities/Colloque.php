<?php

namespace App\Droit\Colloque\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Colloque extends Model
{

    use SoftDeletes;

    protected $table = 'colloques';

    protected $dates = ['deleted_at','start_at','end_at','registration_at','active_at'];

    protected $fillable = [
        'titre', 'soustitre', 'sujet', 'remarques', 'start_at', 'end_at', 'registration_at', 'active_at', 'organisateur',
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
            return 'files/colloques/illustration/'.$illustration->first()->path;
        }
        else
        {
            return 'files/colloques/illustration/illu.png';
        }
    }

    public function getEventDateAttribute()
    {
        setlocale(LC_ALL, 'fr_FR.UTF-8');

        if(isset($this->end_at) && ($this->start_at != $this->end_at))
        {
            $month  = ($this->start_at->month == $this->end_at->month ? '%d' : '%d %B');
            $year   = ($this->start_at->year ==  $this->end_at->year ? '' : '%Y');
            $format = $month.' '.$year;

            return 'Du '.$this->start_at->formatLocalized($format).' au '.$this->end_at->formatLocalized('%d %B %Y');
        }
        else
        {
            return 'Le '.$this->start_at->formatLocalized('%d %B %Y');
        }
    }

    public function getAnnexeAttribute()
    {
        if($this->bon)
        {
            $annexes[] = 'bon';
        }

        if($this->facture)
        {
            $annexes[] = 'facture';
            $annexes[] = 'bv';
        }

        return $annexes;
    }

    public function location()
    {
        return $this->belongsTo('App\Droit\Location\Entities\Location');
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
