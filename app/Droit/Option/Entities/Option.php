<?php namespace App\Droit\Option\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Option extends Model{

    use SoftDeletes;

    protected $table = 'colloque_options';

    protected $dates = ['deleted_at'];

    protected $fillable = array('colloque_id','title','type');

    public function colloque()
    {
        return $this->belongsTo('App\Droit\Colloque\Entities\Colloque');
    }

    public function groupe()
    {
        return $this->belongsToMany('App\Droit\Option\Entities\OptionGroupe','colloque_option_users','option_id','groupe_id')->groupBy('id');
    }

}