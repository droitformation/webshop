<?php namespace App\Droit\Categorie\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Categorie extends Model {

    use SoftDeletes;

	protected $fillable = ['title','image','site_id','ismain','hideOnSite'];
    protected $dates    = ['created_at','updated_at','deleted_at'];

    /**
     * Scope a query to only include arrets for site
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSites($query,$site)
    {
        if (isset($site)) $query->where('site_id','=',$site);
    }

    public function arrets()
    {
        $database = $this->getConnection()->getDatabaseName();
        return $this->belongsToMany('\App\Droit\Arret\Entities\Arret', $database.'.arret_categories', 'categories_id', 'arret_id');
    }

    public function site()
    {
        return $this->belongsTo('\App\Droit\Site\Entities\Site');
    }
}