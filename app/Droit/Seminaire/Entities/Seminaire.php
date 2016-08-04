<?php namespace App\Droit\Seminaire\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Seminaire extends Model{

    use SoftDeletes;
    
    protected $table = 'seminaires';

    protected $fillable = ['title','year','image', 'product_id'];

    public function subjects()
    {
        return $this->belongsToMany('App\Droit\Seminaire\Entities\Subject', 'seminaire_subjects','seminaire_id', 'subject_id');
    }

    public function product()
    {
        return $this->belongsTo('App\Droit\Shop\Product\Entities\Product','product_id');
    }

    public function colloques()
    {
        return $this->belongsToMany('App\Droit\Colloque\Entities\Colloque' , 'seminaire_colloques', 'seminaire_id', 'colloque_id');
    }
}