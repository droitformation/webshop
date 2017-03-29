<?php namespace App\Droit\Adresse\Worker;

class AdresseWorker{

    public function prepareTerms($terms, $type)
    {
        $terms = array_filter($terms);

        return !empty($terms) ? collect($terms)->transpose()->map(function ($term) {
            return ['value' => $term[0], 'column' => $term[1]];
        })->filter(function ($terms, $key) use ($type) {
            return !empty($terms['value']) && $this->authorized($terms['column'],$type) ? true : false;
        })->toArray() : [];
    }

    public function authorized($column,$type)
    {
        $authorized = ['user' => ['first_name', 'last_name', 'email'], 'adresse' => ['first_name', 'last_name', 'email','company','adresse','npa', 'ville']];

        return in_array($column, $authorized[$type]);
    }

}