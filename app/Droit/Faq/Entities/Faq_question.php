<?php namespace App\Droit\Faq\Entities;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Faq_question extends Model {

    protected $table      = 'faq_questions';
    protected $fillable   = ['id','site_id', 'rang', 'question', 'answer', 'title', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSites($query,$site)
    {
        if ($site) $query->where('site_id','=',$site);
    }

    public function scopeCategorie($query,$categorie)
    {
        if ($categorie) $query->whereHas('categories', function ($query) use ($categorie) {
            $query->where('categorie_id', '=', $categorie);
        });
    }

    public function categories()
    {
        return $this->belongsToMany('\App\Droit\Faq\Entities\Faq_categorie', 'faq_question_categories', 'question_id', 'categorie_id');
    }
}