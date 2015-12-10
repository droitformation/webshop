<?php

namespace App\Droit\Abo\Worker;

interface AboWorkerInterface{

    public function make($facture_id,$rappel = false);
    public function merge($files, $name, $abo_id);
    public function update($abonnement);
}