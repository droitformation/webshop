<?php namespace App\Droit\Adresse\Entities;

use Illuminate\Database\Eloquent\Model;

class Adresse_types extends Model {

    protected $table     = 'adresse_types';

    protected $fillable  = ['type'];

	public $timestamps   = false;

}
