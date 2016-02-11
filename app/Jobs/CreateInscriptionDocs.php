<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Droit\Inscription\Entities\Inscription;

class CreateInscriptionDocs extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $inscription;
    protected $generator;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Inscription $inscription)
    {
        $this->inscription = $inscription;
        setlocale(LC_ALL, 'fr_FR.UTF-8');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $generator = \App::make('App\Droit\Generate\Pdf\PdfGeneratorInterface');

        $this->inscription->load('colloque');
        $annexes = $this->inscription->colloque->annexe;

        // Generate annexes if any
        if(!empty($annexes))
        {
            foreach($annexes as $annexe)
            {
                $doc = $annexe.'Event';
                $generator->$doc($this->inscription);
            }
        }
    }
}
