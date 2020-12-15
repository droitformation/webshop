<?php namespace App\Droit\Sondage\Entities;

class Transform
{
    static function make($avis,$key){

        $sort = preg_replace('/[^a-z]/i', '', trim(strip_tags($avis->question)));
        $rang = $row->pivot->rang ?? $key;

        $avis->setAttribute('rang',$rang);
        $avis->setAttribute('alpha',strtolower($sort));
        $avis->setAttribute('class',null);
        $avis->setAttribute('choices_list',$avis->choices ? explode(',', $avis->choices) : null);
        $avis->setAttribute('type_name',$avis->type_name);
        $avis->setAttribute('question_simple',strip_tags($avis->question));

        return $avis;
    }
}