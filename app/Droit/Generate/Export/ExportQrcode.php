<?php

namespace App\Droit\Generate\Export;

class ExportQrcode
{
    public function export($inscriptions,$colloque)
    {
        return \PDF::loadView('backend.export.qrcode', ['inscriptions' => $inscriptions])->setPaper('a4')->stream('qrcodes_' . $colloque->id . '.pdf');
    }
}