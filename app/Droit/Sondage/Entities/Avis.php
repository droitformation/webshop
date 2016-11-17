<?php

namespace App\Droit\Sondage\Entities;

use Illuminate\Database\Eloquent\Model;

class Avis extends Model{

    protected $table = 'sondage_avis';

    protected $fillable = ['type','question','choices'];

    public function getTypeNameAttribute()
    {
        $types = ['text' => 'Texte', 'checkbox' => 'Case à cocher', 'radio' => 'Option à choix'];

        return isset($types[$this->type]) ? $types[$this->type] : $this->type;
    }

}