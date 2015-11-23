<?php namespace App\Droit\Abo\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Abo extends Model{

    use SoftDeletes;

    protected $table = 'abos';

    protected $fillable = array('title','product_id','plan');

    public function product()
    {
        return $this->belongsTo('App\Droit\Shop\Product\Entities\Product');
    }

    public function users()
    {
        return $this->belongsToMany('App\Droit\User\Entities\User', 'abo_users', 'abo_id', 'user_id')->withTimestamps();
    }
}