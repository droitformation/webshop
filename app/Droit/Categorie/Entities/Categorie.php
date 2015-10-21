<?php namespace App\Droit\Categorie\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Categorie extends Model {

    use SoftDeletes;

	protected $fillable = ['title','image','ismain','hideOnSite'];
    protected $dates    = ['created_at','updated_at','deleted_at'];

    public function categorie_arrets()
    {
        return $this->belongsToMany('\App\Droit\Arret\Entities\Arret', 'arret_categories', 'categories_id', 'arret_id');
    }
}