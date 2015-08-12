<?php namespace App\Droit\Option\Entities;

use Illuminate\Database\Eloquent\Model;

class Option extends Model{

    protected $table = 'colloque_options';

    protected $fillable = array('colloque_id','title','type');

    public $timestamps = false;

    public function colloque()
    {
        return $this->belongsTo('App\Droit\Colloque\Entities\Colloque');
    }

    public function groupe()
    {
        return $this->belongsToMany('App\Droit\Option\Entities\OptionGroupe','colloque_option_users','option_id','groupe_id')->groupBy('id');
    }
}