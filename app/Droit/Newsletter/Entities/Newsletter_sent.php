<?php namespace App\Droit\Newsletter\Entities;

use Illuminate\Database\Eloquent\Model;

class Newsletter_sent extends Model {

    protected $table = 'newsletter_sent';
    protected $dates = ['send_at'];

	protected $fillable = ['campagne_id','list_id','send_at'];

}