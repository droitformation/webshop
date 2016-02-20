<?php

namespace App\Droit\User\Worker;

interface DuplicateWorkerInterface
{
    public function assign($user_id, $data);
}