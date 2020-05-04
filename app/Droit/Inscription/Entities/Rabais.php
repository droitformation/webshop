<?php

namespace App\Droit\Inscription\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rabais extends Model
{
    use SoftDeletes;

    public $timestamps = false;

    protected $dates = ['deleted_at','expire_at'];
    protected $table = 'inscription_rabais';

    protected $fillable = ['value', 'title','expire_at'];

    public function colloques()
    {
        return $this->belongsToMany('App\Droit\Colloque\Entities\Colloque' , 'inscription_rabais_colloques', 'rabais_id', 'colloque_id');
    }

    public function inscriptions()
    {
        return $this->hasMany('App\Droit\Inscription\Entities\Inscription','rabais_id');
    }
}
