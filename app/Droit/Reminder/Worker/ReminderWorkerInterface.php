<?php

namespace App\Droit\Reminder\Worker;

interface ReminderWorkerInterface
{
    public function add($attribut, $product, $title ,$interval);
}