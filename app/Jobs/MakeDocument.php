<?php

namespace App\Jobs;

use App\Jobs\Job;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Droit\Inscription\Entities\Inscription;

class MakeDocument extends Job
{
    use SerializesModels;

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
        $this->inscription->load('colloque');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $generator = \App::make('App\Droit\Generate\Pdf\PdfGeneratorInterface');

        $annexes   = $this->inscription->colloque->annexe;

        // Generate annexes if any
        if(!empty($annexes))
        {
            foreach ($annexes as $annexe)
            {
                // Make the bon and the other docs if the price is not 0
                if($annexe == 'bon' || ($this->inscription->price_cents > 0 && ($annexe == 'facture' || $annexe == 'bv')))
                {
                    $generator->make($annexe, $this->inscription);
                }
            }
        }
    }
}
