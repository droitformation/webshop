<?php

namespace App\Droit\Adresse\Worker;

interface AdresseWorkerInterface
{
    public function removeSession();
    public function reassignFor($recipient);
    
    public function prepareTerms($terms, $type);
    public function authorized($column,$type);
}