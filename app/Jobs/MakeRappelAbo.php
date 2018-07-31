<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class MakeRappelAbo extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $facture;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($facture)
    {
        $this->facture  = $facture;

        setlocale(LC_ALL, 'fr_FR.UTF-8');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $worker = \App::make('App\Droit\Abo\Worker\AboRappelWorkerInterface');

        $worker->makeRappel($this->facture);
    }
}
