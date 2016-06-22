<?php namespace App\Droit\Arret\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Arret extends Model {

    use SoftDeletes;

	protected $fillable = ['site_id','user_id','reference','pub_date','abstract','pub_text','file'];
    protected $dates    = ['pub_date'];

    /**
     * Scope a query to only include arrets for site
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSite($query,$site)
    {
        if ($site) $query->where('site_id','=',$site);
    }

    public function scopeTrashed($query,$trashed)
    {
        if ($trashed) $query->withTrashed();
    }

    public function categories()
    {
        return $this->belongsToMany('\App\Droit\Categorie\Entities\Categorie', 'arret_categories', 'arret_id', 'categories_id')->withPivot('sorting')->orderBy('sorting', 'asc');
    }

    public function analyses()
    {
        return $this->belongsToMany('\App\Droit\Analyse\Entities\Analyse', 'analyses_arret', 'arret_id', 'analyse_id')->withPivot('sorting')->orderBy('sorting', 'asc');
    }

    public function site()
    {
        return $this->belongsTo('\App\Droit\Site\Entities\Site');
    }
}
