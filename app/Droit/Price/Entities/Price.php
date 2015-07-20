<?php namespace App\Droit\Price\Entities;

use Illuminate\Database\Eloquent\Model;

class Price extends Model{

    protected $table = 'colloque_prices';

    protected $fillable = array('colloque_id','price','type','description');

    public $timestamps = false;

}