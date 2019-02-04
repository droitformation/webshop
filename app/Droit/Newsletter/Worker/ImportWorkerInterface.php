<?php

namespace App\Droit\Newsletter\Worker;

interface ImportWorkerInterface
{
    public function import($data,$file);
    public function subscribe($results, $list = null);
    public function read($file);
    public function store($file);
    public function sync($file,$list);
    public function syncSpecialisations($newsletter_id);
}