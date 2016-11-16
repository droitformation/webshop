<?php

namespace App\Droit\Sondage\Entities;

use Illuminate\Database\Eloquent\Model;

class Question extends Model{

    protected $table = 'sondage_questions';

    protected $fillable = ['type','question','choices'];

    /**
     * Set timestamps off
     */
    public $timestamps = false;
}