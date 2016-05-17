<?php

namespace App\Droit\Generate\Pdf;

interface PdfBadgeInterface
{
    public function export($colloque_id,$inscriptions,$badge);

    public function chunkData($data,$cols,$nbr);
}