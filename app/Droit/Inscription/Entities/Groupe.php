<?php

namespace App\Droit\Inscription\Entities;

use Illuminate\Database\Eloquent\Model;

class Groupe extends Model
{

    protected $table = 'colloque_inscriptions_groupes';

    public $timestamps = false;

    protected $fillable = ['colloque_id', 'user_id', 'description', 'adresse_id'];

    public function user()
    {
        return $this->belongsTo('App\Droit\User\Entities\User');
    }

}
