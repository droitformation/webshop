<?php
namespace App\Droit\Inscription\Worker;

interface InscriptionWorkerInterface{

    // Register inscription simple and grouped
    public function register($data,$colloque_id, $simple = false);
    public function registerGroup($colloque, $request);

    // Send via admin
    public function prepareData($model);
    public function makeDocuments($model,$refresh = false);
    public function destroyDocuments($model);
    public function sendEmail($model, $email);
    public function updateInscription($model);
}