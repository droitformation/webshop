<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class MakeRappelInscription extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $worker;
    protected $inscription;
    protected $inscriptions;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($inscriptions)
    {
        $this->worker       = \App::make('App\Droit\Inscription\Worker\RappelWorkerInterface');
        $this->inscription  = \App::make('App\Droit\Inscription\Repo\InscriptionInterface');
        $this->inscriptions = $inscriptions;

        setlocale(LC_ALL, 'fr_FR.UTF-8');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if(empty($this->inscriptions)){ return true; }

        $inscriptions = $this->inscription->getMultiple($this->inscriptions);

        if(!$inscriptions->isEmpty())
        {
            foreach($inscriptions as $inscription) {

                $rappel = $inscription->list_rappel->sortBy('created_at')->last();

                if(!$rappel)
                {
                    if ($inscription->group_id)
                    {
                        $this->worker->generateMultiple($inscription->groupe);
                    }
                    else {
                        $this->worker->generateSimple($inscription);
                    }
                }
            }
        }
    }

}
