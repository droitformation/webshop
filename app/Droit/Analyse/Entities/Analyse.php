<?php namespace App\Droit\Analyse\Entities;

use Illuminate\Database\Eloquent\Model;

class Analyse extends Model {

    protected $table    = 'analyses';
    protected $fillable = ['user_id', 'pub_date','abstract','file','site_id','title'];
    protected $dates    = ['pub_date','created_at','updated_at'];

    public function getDocumentAttribute()
    {
        return !empty($this->file ) && \File::exists(public_path('files/analyses/'.$this->file)) ? $this->file : null;
    }

    public function getFilterAttribute()
    {
        return $this->categories->map(function ($categorie, $key) {
            return 'c'.$categorie->id;
        })->implode(' ');
    }

    /**
     * Scope a query to only include arrets for site
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSite($query,$site)
    {
        if ($site) $query->where('site_id','=',$site);
    }

    public function scopeYears($query, $years)
    {
        if(isset($years) && !empty($years))
        {
            $query->whereIn(\DB::raw("year(pub_date)"), $years)->get();
        }
    }

    public function categories()
    {
        $database = $this->getConnection()->getDatabaseName();
        return $this->belongsToMany('\App\Droit\Categorie\Entities\Categorie', $database.'.analyse_categories', 'analyse_id', 'categories_id')->withPivot('sorting')->orderBy('sorting', 'asc');
    }
    
	public function arrets()
    {
        $database = $this->getConnection()->getDatabaseName();
        return $this->belongsToMany('\App\Droit\Arret\Entities\Arret', $database.'.analyses_arret', 'analyse_id', 'arret_id')->withPivot('sorting')->orderBy('sorting', 'asc');
    }

    public function authors()
    {
        $database = $this->getConnection()->getDatabaseName();
        return $this->belongsToMany('\App\Droit\Author\Entities\Author', $database.'.analyse_authors', 'analyse_id', 'author_id')->withPivot('sorting')->orderBy('last_name', 'asc');
    }

    public function site()
    {
        return $this->belongsTo('\App\Droit\Site\Entities\Site');
    }
}
