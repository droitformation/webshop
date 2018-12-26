<?php namespace App\Droit\Arret\Entities;

use Illuminate\Database\Eloquent\Model;

class Groupe extends Model {

    /**
     * Set timestamps off
     */
    public $timestamps = false;

	protected $fillable = ['categorie_id'];

    public function arrets()
    {
        $database = $this->getConnection()->getDatabaseName();
        return $this->belongsToMany('\App\Droit\Arret\Entities\Arret', $database.'.arrets_groupes', 'groupe_id', 'arret_id')->withPivot('sorting')->orderBy('sorting', 'asc');
    }

    public function categorie()
    {
        return $this->hasOne('\App\Droit\Categorie\Entities\Categorie','id', 'categorie_id');
    }
}
