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
    protected $options;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($facture, $options)
    {
        $this->facture = $facture;
        $this->options = $options;

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

        $print = $this->options['print'] ?? null;
        $new   = $this->options['new'] ?? null;

        $worker->makeRappel($this->facture, $new, $print); // facture model, new , print
    }
}
