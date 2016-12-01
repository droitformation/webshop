<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class MakeRappelAbo extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $rappel;
    protected $facture;
    protected $factures;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($factures)
    {
        $this->rappel     = \App::make('App\Droit\Abo\Repo\AboRappelInterface');
        $this->facture    = \App::make('App\Droit\Abo\Repo\AboFactureInterface');
        $this->factures   = $factures;

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

        if(empty($this->factures)){ return true; }

        $factures = $this->facture->getMultiple($this->factures);

        foreach($factures as $facture)
        {
            // Make rappel if not exist
            if($facture->rappels->isEmpty())
            {
                $rappel  = $this->rappel->create(['abo_facture_id' => $facture->id]);
                
                $generator->makeAbo('rappel', $facture, 1, $rappel);
            }
        }
    }
}
