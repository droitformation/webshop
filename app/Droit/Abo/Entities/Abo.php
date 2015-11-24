<?php namespace App\Droit\Abo\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Abo extends Model{

    use SoftDeletes;

    protected $table = 'abos';

    protected $fillable = array('title','product_id','plan');

    public function getPlanFrAttribute()
    {
        $traduction = ['year' => 'Annuel', 'semester' => 'Semestriel', 'month' => 'Mensuel'];

        return $traduction[$this->plan];
    }

    public function product()
    {
        return $this->belongsTo('App\Droit\Shop\Product\Entities\Product');
    }

    public function abonnements()
    {
        return $this->hasMany('App\Droit\Abo\Entities\Abo_users','abo_id');
    }
}