<?php namespace App\Droit\Occurrence\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Occurrence extends Model{

    use SoftDeletes;

    protected $table = 'colloque_occurrences';

    protected $dates = ['deleted_at','starting_at'];

    protected $fillable = ['colloque_id','title','lieux_id','starting_at','full'];

    public function getIsActiveAttribute()
    {
        return $this->starting_at >= \Carbon\Carbon::today() ? true : false;
    }

    public function colloque()
    {
        return $this->belongsTo('App\Droit\Colloque\Entities\Colloque');
    }

    public function location()
    {
        return $this->belongsTo('App\Droit\Location\Entities\Location','lieux_id');
    }
}