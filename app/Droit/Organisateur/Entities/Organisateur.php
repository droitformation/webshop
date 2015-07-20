<?php namespace App\Droit\Organisateur\Entities;

use Illuminate\Database\Eloquent\Model;

class Organisateur extends Model{

    protected $table = 'organisateurs';

    protected $fillable = array('name','description','url','logo');

    public $timestamps = false;

}