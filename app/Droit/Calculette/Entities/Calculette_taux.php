<?php namespace App\Droit\Calculette\Entities;

use Illuminate\Database\Eloquent\Model;

class Calculette_taux extends Model {

	public $timestamps  = false;
	protected $fillable = ['canton','start_at','taux'];
	protected $date     = ['start_at'];
	protected $table    = 'calculette_taux';
	
}
