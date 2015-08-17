<?php

namespace App\Droit\Inscription\Entities;

use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{

    protected $table = 'colloque_inscriptions_participants';

    public $timestamps = false;

    protected $fillable = ['name', 'inscription_id'];

}
