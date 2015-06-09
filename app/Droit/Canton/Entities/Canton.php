<?php namespace App\Droit\Canton\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Canton extends Model{

    protected $table = 'cantons';

    protected $fillable = array('title');
    /**
     * Set timestamps off
     */
    public $timestamps = false;

}