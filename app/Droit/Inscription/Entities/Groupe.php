<?php

namespace App\Droit\Inscription\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Groupe extends Model
{
    use SoftDeletes;

    protected $table = 'colloque_inscriptions_groupes';

    protected $dates = ['deleted_at'];

    public $timestamps = false;

    protected $fillable = ['colloque_id', 'user_id', 'description', 'adresse_id'];

    public function getNameAttribute()
    {
        $this->load('user');

        return $this->user->name;
    }

    public function getParticipantListAttribute()
    {
        $this->load('inscriptions.participant');

        return $this->inscriptions->pluck('participant.name','inscription_no')->all();
    }

    public function getOccurrenceListAttribute()
    {
        $occurrences = new \Illuminate\Database\Eloquent\Collection();

        $this->load('inscriptions');

        if(!$this->inscriptions->isEmpty())
        {
            foreach($this->inscriptions as $inscription)
            {
                $occurrences = $occurrences->merge($inscription->occurrences);
            }
        }

        return $occurrences;
    }

    public function getDocFactureAttribute()
    {
        $path = config('documents.colloque.facture');
        $file = $path.'facture_'.$this->colloque_id.'-'.$this->id.'-'.$this->user_id.'.pdf';

        if (\File::exists(public_path($file)))
        {
            return $file;
        }

        return null;
    }

    public function getDocBvAttribute()
    {
        $path = config('documents.colloque.bv');
        $file = $path.'bv_'.$this->colloque_id.'-'.$this->id.'-'.$this->user_id.'.pdf';

        if (\File::exists(public_path($file)))
        {
            return $file;
        }

        return null;
    }

    public function getPriceAttribute()
    {
        $this->load('inscriptions');

        $somme = 0;

        if(!$this->inscriptions->isEmpty())
        {
            foreach($this->inscriptions as $inscription)
            {
                $somme += $inscription->price_cents;
            }
        }

        $money = new \App\Droit\Shop\Product\Entities\Money;

        return $money->format($somme);
    }

    public function getDocumentsAttribute()
    {
        $docs = [];

        $this->load('user','colloque','inscriptions','inscriptions.participant');

        if(!empty($this->colloque->annexe))
        {
            $annexes = $this->colloque->annexe;

            if(in_array('bon',$annexes))
            {
                foreach($this->inscriptions as $inscription)
                {
                    $docs[] = $this->getFile('bon',$inscription->participant->id);
                }
            }

            if(in_array('facture',$annexes))
            {
                $docs[] = $this->getFile('facture');
                $docs[] = $this->getFile('bv');
            }
        }

        return $docs;
    }

    public function getFile($annexe,$part = false)
    {
        $part = ($part ? '-'.$part : '');
        $path = config('documents.colloque.'.$annexe);

        $name = ($annexe == 'bon' ? $annexe.'_'.$this->colloque_id.'-'.$this->id.$part.'.pdf' : $annexe.'_'.$this->colloque_id.'-'.$this->id.'-'.$this->user_id.$part.'.pdf');

        $file = public_path($path.$name);

        if (\File::exists($file))
        {
            return ['file' => $file, 'name' => $name];
        }

        return null;
    }

    public function user()
    {
        return $this->belongsTo('App\Droit\User\Entities\User');
    }

    public function colloque()
    {
        return $this->belongsTo('App\Droit\Colloque\Entities\Colloque');
    }

    public function inscriptions()
    {
        return $this->hasMany('App\Droit\Inscription\Entities\Inscription', 'group_id');
    }

    public function rappels()
    {
        return $this->hasMany('App\Droit\Inscription\Entities\Rappel', 'group_id');
    }
}
