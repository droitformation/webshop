<?php namespace App\Droit\Arret\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Arret extends Model {

    use SoftDeletes;

	protected $fillable = ['site_id','user_id','reference','pub_date','abstract','pub_text','file','dumois'];
    protected $dates    = ['pub_date'];

    public function getDocumentAttribute()
    {
        return (!empty($this->file) && \File::exists(public_path('files/arrets/'.$this->site->slug.'/'.$this->file))) ? $this->site->slug.'/'.$this->file : null;
    }

    public function getFilenameAttribute()
    {
        if(\File::exists(public_path('files/arrets/'.$this->site->slug.'/'.$this->file))){
            return $this->site->slug.'/'.$this->file;
        }

        if(\File::exists(public_path('files/arrets/'.$this->file))){
            return $this->file;
        }

        return null;
    }

    public function getFilterAttribute()
    {
        return $this->categories->map(function ($categorie, $key) {
            return 'c'.$categorie->id;
        })->implode(' ');
    }

    public function getTitleAttribute()
    {
        setlocale(LC_ALL, 'fr_FR.UTF-8');

        return $this->reference.' du '.$this->pub_date->formatLocalized('%d %B %Y');;
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

    public function scopeTrashed($query,$trashed)
    {
        if ($trashed) $query->withTrashed();
    }

    public function scopeDefault($query,$categories,$years)
    {
        if (empty($categories) && empty($years)) $query->take(10);
    }

    public function scopeCategories($query, $only)
    {
        if($only)
        {
            foreach($only as $categorie)
            {
                $query->whereHas('categories', function($query) use ($categorie){
                    $query->where('categories_id', '=' ,$categorie);
                });
            }
        }
    }

    public function scopeAnalyses($query, $only)
    {
        if($only == 'true')
        {
            $query->has('analyses');
        }
    }

    public function scopeYears($query, $years)
    {
        if(!empty($years))
        {
            $query->whereIn(\DB::raw("year(pub_date)"), $years)->get();
        }
    }

    public function categories()
    {
        $database = $this->getConnection()->getDatabaseName();
        return $this->belongsToMany('\App\Droit\Categorie\Entities\Categorie', $database.'.arret_categories', 'arret_id', 'categories_id')->withPivot('sorting')->orderBy('sorting', 'asc');
    }

    public function analyses()
    {
        $database = $this->getConnection()->getDatabaseName();
        return $this->belongsToMany('\App\Droit\Analyse\Entities\Analyse', $database.'.analyses_arret', 'arret_id', 'analyse_id')->withPivot('sorting')->orderBy('sorting', 'asc');
    }

    public function site()
    {
        return $this->belongsTo('\App\Droit\Site\Entities\Site');
    }

    public function campagnes()
    {
        return $this->hasManyThrough(
            '\App\Droit\Newsletter\Entities\Newsletter_campagnes',
            '\App\Droit\Newsletter\Entities\Newsletter_contents',
            'arret_id',
            'id',
            'id',
            'newsletter_campagne_id'
        );
    }

}
