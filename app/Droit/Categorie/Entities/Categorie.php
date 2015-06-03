<?php namespace App\Droit\Categorie\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Categorie extends Model{

    use SoftDeletes;

    protected $table = 'categories';

    protected $dates = ['deleted_at'];

    protected $fillable = array('title', 'sorting', 'deleted' ,'hidden');
    /**
     * Set timestamps off
     */
    public $timestamps = false;

    public function parent()
    {
        return $this->hasOne('App\Hub\Shop\Categorie\Entities\Parent_categories','categorie_id', 'id');
    }

}