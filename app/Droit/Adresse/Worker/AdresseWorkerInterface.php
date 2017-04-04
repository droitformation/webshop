<?php
/**
 * Created by PhpStorm.
 * User: cindyleschaud
 * Date: 03.04.17
 * Time: 09:01
 */

namespace App\Droit\Adresse\Worker;

interface AdresseWorkerInterface
{
    public function fetchUser($adresse_id);
    public function reassignFor($recipient);
    
    public function prepareTerms($terms, $type);
    public function authorized($column,$type);
}