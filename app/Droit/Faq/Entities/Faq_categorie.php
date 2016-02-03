<?php namespace App\Droit\Faq\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Faq_categorie extends Model {

    use SoftDeletes;

    protected $dates      = ['deleted_at'];
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

    public function questions()
    {
        return $this->belongsToMany('\App\Droit\Faq\Entities\Faq_question', 'faq_question_categories', 'categorie_id', 'question_id')->orderBy('faq_questions.rang');
    }

}

