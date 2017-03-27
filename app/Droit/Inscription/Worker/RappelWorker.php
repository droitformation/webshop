<?php

namespace App\Droit\Inscription\Worker;

use App\Droit\Inscription\Worker\RappelWorkerInterface;
use App\Droit\Inscription\Repo\RappelInterface;

class RappelWorker implements RappelWorkerInterface
{
    protected $rappel;
    protected $generator;

    public function __construct(RappelInterface $rappel)
    {
        $this->rappel    = $rappel;
        $this->generator = \App::make('App\Droit\Generate\Pdf\PdfGeneratorInterface');
    }

    public function generateSimple($inscription)
    {
        $rappel = $this->rappel->create([
            'colloque_id'    => $inscription->colloque_id,
            'inscription_id' => $inscription->id,
            'user_id'        => $inscription->user_id,
            'group_id'       => $inscription->group_id
        ]);

        $this->generator->make('facture', $inscription, $rappel);

        return $rappel;
    }

    public function generateMultiple($group)
    {
        $rappel = $this->rappel->create([
            'colloque_id'    => $group->colloque_id,
            'inscription_id' => null,
            'user_id'        => $group->user_id,
            'group_id'       => $group->id
        ]);

        $this->generator->make('facture', $group, $rappel);

        return $rappel;
    }

    public function generate($inscription)
    {
        if (isset($inscription->groupe)) {
            $this->generateMultiple($inscription->groupe);
        } else {
            $this->generateSimple($inscription);
        }
    }

    public function make($inscriptions, $makemore = false)
    {
        if(!$inscriptions->isEmpty()) {
            foreach ($inscriptions as $inscription) {

                $rappel = $inscription->list_rappel->sortBy('created_at')->last();
                
                if($makemore || !$rappel){
                    $this->generate($inscription);
                }
            }
        }
    }
}