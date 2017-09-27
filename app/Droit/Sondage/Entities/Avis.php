<?php

namespace App\Droit\Sondage\Entities;

use Illuminate\Database\Eloquent\Model;

class Avis extends Model{

    protected $table = 'sondage_avis';

    protected $fillable = ['type','question','choices'];

    public function getTypeNameAttribute()
    {
        $types = ['text' => 'Texte', 'checkbox' => 'Case à cocher', 'radio' => 'Option à choix', 'Chapitre'=> 'Chapitre'];

        return isset($types[$this->type]) ? $types[$this->type] : $this->type;
    }

    public function responses()
    {
        return $this->hasMany('App\Droit\Sondage\Entities\Sondage_reponse', 'avis_id', 'id');
    }

}