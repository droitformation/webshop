<?php namespace App\Droit\Price\Entities;

use Illuminate\Database\Eloquent\Model;

/*
 * ONLY TEMPORARY MODEL
 * REMOVE AFTER CONVERSION
 **/

class Prix extends Model{

    protected $table = 'prix';

    protected $fillable = array('colloque_id','price','type','description','rang');

    public $timestamps = false;

}