<?php

namespace App\Droit\Sondage\Entities;

use Illuminate\Database\Eloquent\Model;

class Avis extends Model{

    protected $table = 'sondage_avis';

    protected $fillable = ['type','question','choices','hidden'];

    public function getTypeNameAttribute()
    {
        $types = ['text' => 'Texte', 'checkbox' => 'Case à cocher', 'radio' => 'Option à choix', 'Chapitre'=> 'Chapitre'];

        return isset($types[$this->type]) ? $types[$this->type] : $this->type;
    }

    public function scopeActive($query, $active = null)
    {
        if($active){
            $query->whereNull('hidden');
        }
        else{
            $query->whereNull('hidden')->orWhereNotNull('hidden');
        }
    }

    public function responses()
    {
        return $this->hasMany('App\Droit\Sondage\Entities\Sondage_reponse', 'avis_id', 'id');
    }

}