<?php

namespace App\Droit\User\Worker;

interface AccountWorkerInterface
{
    public function setAdresse($adresse);
    public function createAccount($data);
    public function makeUser();
    public function prepareData($data);
}