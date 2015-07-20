<?php namespace App\Droit\Location\Entities;

use Illuminate\Database\Eloquent\Model;

class Location extends Model{

    protected $table = 'locations';

    protected $fillable = array('name','adresse','url','map');

    public $timestamps = false;

}