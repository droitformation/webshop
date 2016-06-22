<?php namespace App\Droit\Analyse\Entities;

use Illuminate\Database\Eloquent\Model;

class Analyse extends Model {

    protected $table    = 'analyses';
    protected $fillable = ['user_id','author','pub_date','abstract','file','site_id','title'];
    protected $dates    = ['pub_date','created_at','updated_at'];

    /**
     * Scope a query to only include arrets for site
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSite($query,$site)
    {
        if ($site) $query->where('site_id','=',$site);
    }

    public function categories()
    {
        return $this->belongsToMany('\App\Droit\Categorie\Entities\Categorie', 'analyse_categories', 'analyse_id', 'categories_id')->withPivot('sorting')->orderBy('sorting', 'asc');
    }
    
	public function arrets()
    {     
        return $this->belongsToMany('\App\Droit\Arret\Entities\Arret', 'analyses_arret', 'analyse_id', 'arret_id')->withPivot('sorting')->orderBy('sorting', 'asc');
    }

    public function authors()
    {
        return $this->belongsToMany('\App\Droit\Author\Entities\Author', 'analyse_authors', 'analyse_id', 'author_id')->withPivot('sorting')->orderBy('last_name', 'asc');
    }

}
