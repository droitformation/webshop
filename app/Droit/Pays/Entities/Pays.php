<?php namespace App\Droit\Pays\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pays extends Model{

    protected $table = 'pays';

    protected $fillable = array('title','code');
    /**
     * Set timestamps off
     */
    public $timestamps = false;

}