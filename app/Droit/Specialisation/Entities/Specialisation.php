<?php

namespace App\Droit\Specialisation\Entities;

use Illuminate\Database\Eloquent\Model;

class Specialisation extends Model{

    protected $table = 'specialisations';

    protected $fillable = ['title'];

    /**
     * Set timestamps off
     */
    public $timestamps = false;

    public function getEmailsAttribute()
    {
        return $this->adresses->pluck('email')->reject(function ($email, $key) {
            return empty(trim($email)) || (!empty($email) && isSubstitute($email));
        })->map(function ($email, $key) {
            return ['Email' => $email];
        })->toArray();
    }

    public function adresses()
    {
        return $this->belongsToMany('App\Droit\Adresse\Entities\Adresse','adresse_specialisations','specialisation_id','adresse_id');
    }

}