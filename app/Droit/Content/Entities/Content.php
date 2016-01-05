<?php namespace App\Droit\Content\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Content extends Model {

    use SoftDeletes;

    protected $dates    = ['deleted_at'];

	protected $fillable = ['title','content','image','url','type','rang','page_id'];

    protected $table = 'contents';

}