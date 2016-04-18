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
    protected $factures;
    protected $product_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($factures, $product_id)
    {
        $this->rappel     = \App::make('App\Droit\Abo\Repo\AboRappelInterface');
        $this->factures   = $factures;
        $this->product_id = $product_id;

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

        // All factures for the product
        foreach($this->factures as $facture)
        {
            $exist = $this->rappel->findByFacture($facture->id);

            // No ned to remake a rappel
            if(!$exist)
            {
                $rappel  = $this->rappel->create(['abo_facture_id' => $facture->id]);
                $rappel->load('facture');

                $rappels = $this->rappel->findByFacture($facture->id);
                $rappels = $rappels->count();

                $generator->makeAbo('rappel', $rappel->facture, $rappels, $rappel);
            }
        }
    }
}
