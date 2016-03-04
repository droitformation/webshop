<?php

namespace App\Droit\Generate\Pdf;

interface PdfGeneratorInterface
{
    public function setMsg($message,$type);
    public function factureOrder($order_id);
    public function factureAbo($abo_id,$facture_id,$rappel = null);
    public function make($document, $model);
    public function getData($document);
}