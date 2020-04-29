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

    protected $fillable = ['colloque_id', 'user_id', 'description','reference_id', 'adresse_id'];

    public function getNameAttribute()
    {
        $this->load('user');

        return $this->user->name;
    }

    public function getRappelListAttribute()
    {
        return $this->rappels->map(function ($item, $key) {
            return ['id' => $item->id ,'date' => 'Rappel '.$item->created_at->format('d/m/Y'), 'doc_rappel' => $item->doc_rappel];
        });
    }

    public function getListRappelAttribute()
    {
        return $this->rappels;
    }

    /*
     * Return Array
     * */
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

        if (\File::exists(public_path($file))) {
            return $file;
        }

        return null;
    }

    public function getDocBvAttribute()
    {
        $path = config('documents.colloque.bv');
        $file = $path.'bv_'.$this->colloque_id.'-'.$this->id.'-'.$this->user_id.'.pdf';

        if (\File::exists(public_path($file))) {
            return $file;
        }

        return null;
    }

    public function getPriceCentsAttribute()
    {
        // TODO
        $this->load('inscriptions');

        $somme = !$this->inscriptions->isEmpty() ? $this->inscriptions->reduce(function ($carry, $inscription) {
            return $carry + $inscription->price_cents;
        },0) :collect([]);

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

            if(in_array('bon',$annexes)) {
                foreach($this->inscriptions as $nbr => $inscription) {
                    $docs[] = $this->getFile('bon',$inscription->participant->id, $nbr+1);
                }
            }

            if(in_array('facture',$annexes)) {
                $docs[] = $this->getFile('facture');
                $docs[] = $this->getFile('bv');
            }
        }

        return array_filter($docs);
    }

    public function getFile($annexe,$part = false, $nbr = '')
    {
        $part = ($part ? '-'.$part : '');
        $path = config('documents.colloque.'.$annexe);

        $name = ($annexe == 'bon' ? $annexe.'_'.$this->colloque_id.'-'.$this->id.$part.'.pdf' : $annexe.'_'.$this->colloque_id.'-'.$this->id.'-'.$this->user_id.$part.'.pdf');

        $file = public_path($path.$name);

        if (\File::exists($file)) {

            return [
                'file' => $file,
                'name' => ucfirst($annexe).$nbr,
                'link' => $path.$name,
                'pdfname' => $name,
                'url'      => asset($path.$name)
            ];
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

    public function references()
    {
        return $this->belongsTo('App\Droit\Transaction\Entities\Transaction_reference','reference_id');
    }
}
