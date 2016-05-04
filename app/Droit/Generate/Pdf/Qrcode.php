<?php

namespace App\Droit\Generate\Pdf;

class Qrcode
{
    public function export($inscriptions,$colloque_id)
    {
        return \PDF::loadView('backend.export.qrcode', ['inscriptions' => $inscriptions])->setPaper('a4')->stream('qrcodes_' . $colloque_id . '.pdf');
    }
}