<?php namespace App\Droit\Civilite\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Civilite extends Model{

    protected $table = 'civilites';

    protected $fillable = array('title');
    /**
     * Set timestamps off
     */
    public $timestamps = false;

}