<?php namespace App\Droit\Site\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Site extends Model{

    use SoftDeletes;

    protected $dates    = ['deleted_at'];

    protected $table    = 'sites';

    protected $fillable = ['nom','url','logo'];

}