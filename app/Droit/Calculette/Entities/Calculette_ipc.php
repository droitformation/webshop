<?php namespace App\Droit\Calculette\Entities;

use Illuminate\Database\Eloquent\Model;

class Calculette_ipc extends Model {

	public $timestamps  = false;
	protected $fillable = ['indice','start_at'];
	protected $date     = ['start_at'];
	protected $table    = 'calculette_ipc';
	
}
