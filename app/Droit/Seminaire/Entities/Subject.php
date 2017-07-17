<?php namespace App\Droit\Seminaire\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subject extends Model{

    use SoftDeletes;
    
    protected $table = 'subjects';

    protected $fillable = ['title','file','appendixes','rang'];

    public function getFilePathAttribute()
    {
        $file = 'files/subjects/'.$this->file;

        return ($this->file && \File::exists(public_path($file)) ? $file : null);
    }

    public function getAnnexePathAttribute()
    {
        if(!empty($this->appendixes)){
            $links = explode(',',$this->appendixes);
            foreach($links as $appendixe){
                $file = 'files/subjects/'.$appendixe;
                $annexes[] = \File::exists(public_path($file)) ? ['link' => $file, 'name' => $appendixe] : null;
            }

            $annexes = array_filter($annexes);
        }

        return (isset($annexes) && !empty($annexes)) ? $annexes : null;
    }

    public function getMainCategorieAttribute()
    {
        if(!$this->categories->isEmpty())
        {
            return mb_strtolower($this->categories->first()->title);
        }

        return 'Général';
    }

    public function getSeminaireAttribute()
    {
        if(!$this->seminaires->isEmpty())
        {
            return $this->seminaires->first();
        }

        return null;
    }

    public function getAuthorsListAttribute()
    {
        return $this->authors->map(function ($author, $key) {
            return 'a'.$author->id;
        })->implode(' ');
    }

    public function seminaires()
    {
        //return $this->belongsTo('App\Droit\Seminaire\Entities\Seminaire');
        return $this->belongsToMany('App\Droit\Seminaire\Entities\Seminaire' , 'seminaire_subjects', 'subject_id', 'seminaire_id');
    }

    public function authors()
    {
        return $this->belongsToMany('App\Droit\Author\Entities\Author' , 'subject_authors', 'subject_id', 'author_id');
    }

    public function categories()
    {
        return $this->belongsToMany('App\Droit\Categorie\Entities\Categorie' , 'subject_categories', 'subject_id', 'categorie_id');
    }
}