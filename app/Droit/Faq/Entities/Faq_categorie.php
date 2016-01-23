<?php namespace App\Droit\Faq\Entities;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Faq_categorie extends Model {

    protected $table      = 'faq_categories';
    protected $fillable   = ['id','site_id','rang','title'];

    /**
     * Scope a query to only include arrets for site
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSites($query,$site)
    {
        if ($site) $query->where('site_id','=',$site);
    }

}

