<?php namespace App\Droit\Analyse\Entities;


class Analyses_arret extends \Illuminate\Database\Eloquent\Model {

	protected $guarded   = array('id');
	public $timestamps   = false;
	protected $table     = 'analyses_arret';
}