<?php

namespace App\Droit\Abo\Worker;

interface AboWorkerInterface{

    public function make($facture_id,$rappel = false);
}