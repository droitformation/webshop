<?php

namespace App\Droit\Inscription\Worker;

interface RappelWorkerInterface
{
    public function generateSimple($inscription);
    public function generateMultiple($group);
    public function make($inscriptions, $makemore = false);
    public function generate($inscription);
    public function generateWithBv($colloque_id);
}