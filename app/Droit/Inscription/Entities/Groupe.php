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

    public function getDocFactureAttribute()
    {
        $path = config('documents.colloque.facture');
        $file = $path.'facture_'.$this->colloque_id.'-'.$this->user_id.'.pdf';

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

        $this->load('user','colloque','inscriptions');

        if(!empty($this->colloque->annexe))
        {
            $annexes = $this->colloque->annexe;

            if(in_array('bon',$annexes))
            {
                $inscriptions = $this->inscriptions;

                foreach($inscriptions as $inscription)
                {
                    $inscription->load('participant');
                    $bons = $this->getFile('bon',$this->colloque->id,$this->id,$inscription->participant->id);
                    $docs['groupe'][] = $bons;
                }

                unset($annexes['bon']);
            }

            foreach($this->colloque->annexe as $id => $annexe)
            {
                $new =  $this->getFile($annexe,$this->colloque->id,$this->id);
                $docs = array_merge($new,$docs);
            }
        }

        return $docs;
    }

    public function getFile($annexe,$colloque_id,$user_id,$part = null)
    {
        $docs = [];
        $part = ($part ? '-'.$part : '');
        $path = config('documents.colloque.'.$annexe.'');
        $file = public_path($path.$annexe.'_'.$colloque_id.'-'.$user_id.$part.'.pdf');

        if (\File::exists($file))
        {
            $name = $annexe.'_'.$colloque_id.'-'.$user_id.$part.'.pdf';

            if($part)
            {
                $docs = ['file' => $file, 'name' => $name];
            }
            else
            {
                $docs[$annexe]['file'] = $file;
                $docs[$annexe]['name'] = $name;
            }
        }

        return $docs;
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
