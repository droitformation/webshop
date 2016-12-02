<?php

namespace App\Droit\Shop\Rappel\Worker;

interface RappelWorkerInterface{
    
    public function generate($order);
}