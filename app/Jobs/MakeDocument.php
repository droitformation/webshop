<?php

namespace App\Jobs;

use App\Jobs\Job;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Droit\Inscription\Entities\Inscription;

class MakeDocument extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $inscription;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Inscription $inscription)
    {
        setlocale(LC_ALL, 'fr_FR.UTF-8');

        $this->inscription = $inscription;
        $this->generator   = new \App\Droit\Generate\Pdf\PdfGenerator();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->inscription->load('colloque');
        $annexes = $this->inscription->colloque->annexe;

        // Generate annexes if any
        if(empty($this->inscription->documents) && !empty($annexes))
        {
            $this->generator->setInscription($this->inscription)->generate($annexes);
        }
    }
}
