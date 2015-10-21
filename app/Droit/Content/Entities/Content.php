<?php namespace App\Droit\Content\Entities;

use Illuminate\Database\Eloquent\Model;

class Content extends Model {

    public $timestamps = false;

	protected $fillable = ['titre','contenu','image','url','slug','type','position','rang'];

    protected $table = 'contents';

}