<?php

namespace App\Droit\Generate\Pdf;

interface QrcodeInterface
{
    public function export($inscriptions,$colloque_id);
}