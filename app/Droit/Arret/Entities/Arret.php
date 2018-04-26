<?php namespace App\Droit\Arret\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Arret extends Model {

    use SoftDeletes;

	protected $fillable = ['site_id','user_id','reference','pub_date','abstract','pub_text','file','dumois'];
    protected $dates    = ['pub_date'];

    public function getDocumentAttribute()
    {
        return (!empty($this->file) && \File::exists(public_path('files/arrets/'.$this->file))) ? $this->file : null;
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

        return $this->reference.' '.$this->pub_date->formatLocalized('%A %d %B %Y');;
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
