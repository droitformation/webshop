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


}