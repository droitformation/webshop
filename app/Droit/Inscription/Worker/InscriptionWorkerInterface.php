<?php
namespace App\Droit\Inscription\Worker;

interface InscriptionWorkerInterface{

    // Register inscription simple and grouped
    public function register($data, $simple = false);

    // Send via admin
    public function prepareData($model, $attachments = []);
    public function makeDocuments($model,$refresh = false);
    public function destroyDocuments($model);
    public function sendEmail($model, $email);
    public function updateInscription($model);
}