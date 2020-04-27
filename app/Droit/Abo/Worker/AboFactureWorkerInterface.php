<?php

namespace App\Droit\Abo\Worker;

interface AboFactureWorkerInterface{

    public function make($facture);
    public function generate($abo, $product, $options);
    public function update($abonnement);
    public function bind($product, $abo);
}