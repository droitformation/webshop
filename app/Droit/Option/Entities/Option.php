<?php namespace App\Droit\Option\Entities;

use Illuminate\Database\Eloquent\Model;

class Option extends Model{

    protected $table = 'colloque_options';

    protected $fillable = array('colloque_id','title','type');

    public $timestamps = false;

}