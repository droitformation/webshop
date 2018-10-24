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

    protected $fillable = ['name', 'inscription_id','email'];

    public function getTheNameAttribute()
    {
        return str_replace(',',' ',$this->name);
    }

    public function inscription()
    {
        return $this->belongsTo('App\Droit\Inscription\Entities\Inscription');
    }

}
