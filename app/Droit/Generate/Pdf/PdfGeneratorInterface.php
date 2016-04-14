<?php

namespace App\Droit\Generate\Pdf;

interface PdfGeneratorInterface
{
    public function setMsg($messages);
    public function factureOrder($order_id);
    public function make($document, $model, $rappel = null);
    public function makeAbo($document, $model, $rappel = null, $rappel_model = null);
    public function getData($document);
}