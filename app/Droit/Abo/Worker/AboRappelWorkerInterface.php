<?php

namespace App\Droit\Abo\Worker;

interface AboRappelWorkerInterface{

    public function make($facture);
    public function generate($product, $abo);
    public function bind($product, $abo);
}