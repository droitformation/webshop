<?php

namespace App\Droit\Generate\Pdf;

use App\Droit\Generate\Pdf\QrcodeInterface;

class Qrcode implements QrcodeInterface
{
    public function export($inscriptions,$colloque_id)
    {
        return \PDF::loadView('backend.export.qrcode', ['inscriptions' => $inscriptions])->setPaper('a4')->stream('qrcodes_' . $colloque_id . '.pdf');
    }
}