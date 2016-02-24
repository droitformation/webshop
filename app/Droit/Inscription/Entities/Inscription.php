<?php

namespace App\Droit\Inscription\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inscription extends Model
{
    use SoftDeletes;

    protected $table = 'colloque_inscriptions';

    protected $dates = ['deleted_at','payed_at','send_at'];

    protected $fillable = ['colloque_id', 'user_id', 'group_id', 'inscription_no', 'price_id', 'payed_at', 'send_at', 'status'];

    public function getPriceCentsAttribute()
    {
        $money = new \App\Droit\Shop\Product\Entities\Money;
        $price = $this->price->price / 100;

        return $money->format($price);
    }

    public function getAnnexeAttribute()
    {
        if(in_array('bon',$this->colloque->annexe))
        {
            return ['bon' => 'bon de participation à présenter à l\'entrée'];
        }
    }

    public function getStatusNameAttribute()
    {
        switch ($this->status) {
            case 'pending':
                $status = [ 'status' => 'En attente', 'color' => 'warning' ];
                break;
            case 'payed':
                $status = [ 'status' => 'Payé', 'color' => 'success' ];
                break;
            case 'free':
                $status = [ 'status' => 'Gratuit', 'color' => 'info' ];
                break;
        }

        return $status;
    }

    public function getDocumentsAttribute()
    {
        $docs = [];

        $this->load('user','groupe');

        if(isset($this->groupe) && !empty($this->groupe))
        {
            $this->groupe->load('user');

            $user = $this->groupe->user;
        }
        else
        {
            $user = $this->user;
        }

        if(!empty($this->colloque->annexe))
        {
            foreach($this->colloque->annexe as $id => $annexe)
            {
                $path = config('documents.colloque.'.$annexe.'');

                $file = public_path().$path.$annexe.'_'.$this->colloque->id.'-'.$user->id.'.pdf';

                if (\File::exists($file))
                {
                    $name = $annexe.'_'.$this->colloque->id.'-'.$user->id.'.pdf';

                    $docs[$annexe]['file'] = $file;
                    $docs[$annexe]['name'] = $name;
                }
            }
        }

        return $docs;
    }

    public function getInscritAttribute()
    {
        $this->load('user','groupe','participant');

        if(isset($this->groupe) && !empty($this->group_id))
        {
            $this->groupe->load('user');

            return $this->groupe->user;
        }

        if($this->user)
        {
            return $this->user;
        }

        return false;
    }

    public function getAdresseFacturationAttribute()
    {
        $this->load('user','groupe','participant');

        if($this->group_id > 0)
        {
            $this->groupe->load('user');
            $this->groupe->user->load('adresses');

            return $this->groupe->user->adresse_contact;
        }

        if($this->user)
        {
            return $this->user->adresse_contact;
        }

        return null;
    }

    /**
     * Scope a query to only include simple inscription
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSimple($query)
    {
        return $query->where('group_id', NULL);
    }

    /**
     * Scope a query to only include multiple inscription
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeMultiple($query)
    {
        return $query->where('user_id', NULL);
    }

    public function price()
    {
        return $this->belongsTo('App\Droit\Price\Entities\Price');
    }

    public function colloque()
    {
        return $this->belongsTo('App\Droit\Colloque\Entities\Colloque');
    }

    public function user()
    {
        return $this->belongsTo('App\Droit\User\Entities\User');
    }

    public function duplicate()
    {
        return $this->belongsTo('App\Droit\Adresse\Entities\Adresse', 'duplicate_id', 'user_id');
    }

    public function groupe()
    {
        return $this->belongsTo('App\Droit\Inscription\Entities\Groupe', 'group_id', 'id');
    }

    public function participant()
    {
        return $this->hasOne('App\Droit\Inscription\Entities\Participant');
    }

    public function user_options()
    {
        return $this->hasMany('App\Droit\Option\Entities\OptionUser');
    }

    public function options()
    {
        return $this->belongsToMany('App\Droit\Option\Entities\Option' , 'colloque_option_users', 'inscription_id', 'option_id');
    }

    public function rappels()
    {
        return $this->hasMany('App\Droit\Inscription\Entities\Rappel');
    }

}
