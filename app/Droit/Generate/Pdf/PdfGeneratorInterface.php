<?php

namespace App\Droit\Generate\Pdf;

interface PdfGeneratorInterface
{
    public function setMsg($message,$type);
    public function factureOrder($order_id);
    public function setInscription($inscription);
    public function bonEvent();
    public function factureEvent($rappel = null);
    public function factureGroupeEvent($groupe,$inscriptions,$price,$rappel = null);
    public function bvGroupeEvent($groupe,$inscriptions,$price);
    public function bvEvent();
    public function factureAbo($abo_id,$facture_id,$rappel = null);
    public function generate($annexes);
}