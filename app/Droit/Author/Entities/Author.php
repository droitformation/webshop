<?php namespace App\Droit\Author\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Author extends Model{

    use SoftDeletes;

    protected $table = 'authors';

    protected $dates = ['deleted_at'];

    protected $fillable = array('first_name', 'last_name','occupation','bio');
    /**
     * Set timestamps off
     */
    public $timestamps = false;

    public function getLastNameSlugAttribute()
    {
        $string = strtolower(htmlentities($this->last_name));
        //Listez ici tous les balises HTML que vous pourriez rencontrer
        $string = preg_replace("/&(.)(acute|cedil|circ|ring|tilde|uml|grave);/", "$1", $string);
        //Tout ce qui n'est pas caractère alphanumérique  -> _
        $string = preg_replace("/([^a-z0-9]+)/", "_", html_entity_decode($string));

        return strtolower($string);
    }

    public function getCompleteNameAttribute()
    {
        return $this->first_name.' '.$this->last_name;
    }

}