<?php namespace App\Droit\Profession\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profession extends Model{

    protected $table = 'professions';

    protected $fillable = array('title');
    /**
     * Set timestamps off
     */
    public $timestamps = false;

}