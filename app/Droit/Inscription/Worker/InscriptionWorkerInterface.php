<?php
namespace App\Droit\Inscription\Worker;

interface InscriptionWorkerInterface{

    public function register($data,$colloque_id, $simple = false);

}