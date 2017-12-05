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

    public function adresses()
    {
        return $this->belongsToMany('App\Droit\Adresse\Entities\Adresse','adresse_specialisations','specialisation_id','adresse_id');
    }

}