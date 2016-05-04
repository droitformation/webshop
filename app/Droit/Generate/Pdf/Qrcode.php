<?php

namespace App\Droit\Generate\Pdf;

class Qrcode
{
    public function export($colloque_id,$inscriptions,$badge)
    {
        $data = $inscriptions->pluck('name_inscription')->all();


        return \PDF::loadView('backend.export.qrcode', $data)->setPaper('a4')->stream('badges_' . $colloque_id . '.pdf');
    }
}