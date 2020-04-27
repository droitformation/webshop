<?php

namespace App\Droit\Abo\Worker;

interface AboRappelWorkerInterface{

    public function make($facture, $print = null);
    public function makeRappel($facture, $new = null, $print = null);
    public function generate($product, $abo, $options);
    public function bind($product, $abo);
}