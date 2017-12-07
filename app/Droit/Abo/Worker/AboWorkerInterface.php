<?php

namespace App\Droit\Abo\Worker;

interface AboWorkerInterface{
    
    public function merge($files, $name, $abo_id, $type);
    public function makeAbonnement($data);
}