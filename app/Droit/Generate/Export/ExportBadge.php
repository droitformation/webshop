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
       // $inscriptions = $inscriptions->pluck('name_inscription')->all();

        $inscriptions = $inscriptions->map(function ($inscription) {

            if($inscription->group_id > 0) {
                $name = $inscription->participant->name;
                $name = explode(' ', $name);
                $name = end($name);
            }
            elseif(isset($inscription->user)) {
                $name = $inscription->user->adresse_contact->last_name;
            }
            else{
                $name = $inscription->user_id;
            }

            return ['name' => $inscription->name_inscription, 'last_name' => str_slug($name)];
        });

        $inscriptions = collect($inscriptions)->sortBy('last_name')->pluck('name')->toArray();

        $data   = $this->chunkData($inscriptions, $this->config['cols'], $this->config['etiquettes']);

        $config = $this->config + ['data' => $data, 'colloque' => $colloque];

        $context = stream_context_create([
            'ssl' => [
                'verify_peer' => FALSE,
                'verify_peer_name' => FALSE,
                'allow_self_signed'=> TRUE
            ]
        ]);
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->setHttpContext($context);

        return $pdf->loadView('backend.export.badge', $config)->setPaper('a4')->download('Badges_colloque_' . $colloque->id . '.pdf');
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