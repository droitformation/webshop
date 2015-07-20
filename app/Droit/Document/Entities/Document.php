<?php namespace App\Droit\Document\Entities;

use Illuminate\Database\Eloquent\Model;

class Document extends Model{

    protected $table = 'colloque_documents';

    protected $fillable = array('colloque_id','display','type','path');

    public $timestamps = false;

}