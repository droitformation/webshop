<?php namespace App\Droit\Reminder\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reminder extends Model{

    use SoftDeletes;

    protected $table    = 'reminders';
    protected $dates    = ['send_at'];
    protected $fillable = ['send_at','start','title','text','type','duration','model_id','model','relation','relation_id'];

}