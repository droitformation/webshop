<?php

namespace App\Jobs;

use App\Jobs\Job;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Droit\Inscription\Entities\Groupe;

class MakeGroupeDocument extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $groupe;
    protected $inscription;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Groupe $groupe)
    {
        setlocale(LC_ALL, 'fr_FR.UTF-8');

        $this->groupe       = $groupe;
        $this->inscription  = \App::make('App\Droit\Inscription\Repo\InscriptionInterface');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->groupe->load('colloque','user');
        $user = $this->groupe->user;
        $user->load('adresses');
        $this->groupe->setAttribute('adresse_facturation',$user->adresse_facturation);

        $annexes      = $this->groupe->colloque->annexe;
        $generator    = new \App\Droit\Generate\Pdf\PdfGenerator();
        $inscriptions = $this->inscription->getByGroupe($this->groupe->id);

        // Generate annexes if any
        if(!$inscriptions->isEmpty())
        {
            foreach($inscriptions as $inscription)
            {
                if(empty($inscription->documents) && !empty($annexes))
                {
                    $generator->setInscription($inscription)->generate($annexes);
                }
            }
        }

        $generator->factureGroupeEvent($this->groupe,$inscriptions);

    }
}
