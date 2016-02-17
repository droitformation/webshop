<?php namespace App\Droit\Reminder\Entities;

use Illuminate\Database\Eloquent\Model;

class Reminder extends Model{

    protected $table    = 'reminders';
    protected $dates    = ['send_at'];
    protected $fillable = ['send_at','title','text','type','interval','model_id','model','relation','relation_id'];

}