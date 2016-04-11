<?php

namespace App\Droit\Generate\Pdf;

class PdfBadge
{
    public function export($colloque_id,$inscriptions,$badge)
    {
        $inscriptions = $inscriptions->pluck('name_inscription')->all();

        $data   = $this->chunkData($inscriptions, $badge['cols'], $badge['etiquettes']);
        $config = $badge + ['data' => $data];

        return \PDF::loadView('backend.export.badge', $config)->setPaper('a4')->stream('badges_' . $colloque_id . '.pdf');
    }

    public function chunkData($data,$cols,$nbr)
    {
        if(!empty($data))
        {
            $chunks = array_chunk($data,$cols);
            $chunks = array_chunk($chunks,$nbr/$cols);

            return $chunks;
        }
        return [];
    }
}