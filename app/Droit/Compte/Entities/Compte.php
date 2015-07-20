<?php namespace App\Droit\Compte\Entities;

use Illuminate\Database\Eloquent\Model;

class Compte extends Model{

    protected $table = 'comptes';

    protected $fillable = array('motif','adresse','compte');

    public $timestamps = false;

}