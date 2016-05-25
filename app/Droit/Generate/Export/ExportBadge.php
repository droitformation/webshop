<?php

namespace App\Droit\Generate\Export;

class ExportBadge
{
    protected $config;
    
    public function setConfig($config)
    {
        $this->config = $config;
    }
    
    public function export($inscriptions, $colloque = null)
    {
        $inscriptions = $inscriptions->pluck('name_inscription')->all();

        $data   = $this->chunkData($inscriptions, $this->config['cols'], $this->config['etiquettes']);

        $config = $this->config + ['data' => $data];

        return \PDF::loadView('backend.export.badge', $config)->setPaper('a4')->stream('badges_' . $colloque->id . '.pdf');
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