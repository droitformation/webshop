<?php namespace App\Droit\Organisateur\Entities;

use Illuminate\Database\Eloquent\Model;

class Organisateur extends Model{

    protected $table = 'organisateurs';

    protected $fillable = ['name','description','url','logo','centre','tva','adresse'];

    public $timestamps  = false;

}