<?php namespace App\Droit\Adresse\Entities;

use Illuminate\Database\Eloquent\Model;

class Adresse_specialisation extends Model {

    protected $table     = 'adresse_specialisations';

    protected $fillable  = ['adresse_id','specialisation_id'];

	public $timestamps   = false;

}
