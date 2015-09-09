<?php

namespace App\Droit\Inscription\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Participant extends Model
{
    use SoftDeletes;

    public $timestamps = false;

    protected $dates = ['deleted_at'];

    protected $table = 'colloque_inscriptions_participants';

    protected $fillable = ['name', 'inscription_id'];

}
