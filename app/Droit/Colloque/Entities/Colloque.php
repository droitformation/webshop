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

    public function getEventDateAttribute()
    {
        setlocale(LC_ALL, 'fr_FR.UTF-8');

        $start_at = \Carbon\Carbon::createFromFormat('Y-m-d',$this->start_at);
        $end_at   = \Carbon\Carbon::createFromFormat('Y-m-d',$this->end_at);

        if($this->start_at == $this->end_at)
        {
            return 'Le '.$start_at->formatLocalized('%d %B %Y');
        }
        else
        {
            $month  = ($start_at->month == $end_at->month ? '%d' : '%d %B');
            $year   = ($start_at->year == $end_at->year ? '' : '%Y');
            $format = $month.' '.$year;

            return 'Du '.$start_at->formatLocalized($format).' au '.$end_at->formatLocalized('%d %B %Y');
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
