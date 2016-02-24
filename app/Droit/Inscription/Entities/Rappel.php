<?php

namespace App\Droit\Inscription\Entities;

use Illuminate\Database\Eloquent\Model;

class Rappel extends Model
{
    protected $table = 'colloque_inscription_rappels';

    protected $fillable = ['user_id', 'inscription_id'];
}
