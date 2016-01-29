<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Droit\Abo\Entities\Abo_factures;

class MakeFactureAbo extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $facture;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Abo_factures $facture)
    {
       $this->facture = $facture;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $generator = new \App\Droit\Generate\Pdf\PdfGenerator();

        $generator->factureAbo($this->facture->abonnement ,$this->facture);

    }
}
