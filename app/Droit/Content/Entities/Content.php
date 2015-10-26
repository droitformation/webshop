<?php namespace App\Droit\Content\Entities;

use Illuminate\Database\Eloquent\Model;
use Baum\Node;

class Content extends Node {

    public $timestamps = false;

	protected $fillable = ['titre','contenu','image','url','slug','type','position','rang','parent_id','lft','rgt','depth'];

    protected $table = 'contents';

}