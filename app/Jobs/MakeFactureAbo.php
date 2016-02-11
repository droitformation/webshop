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
    protected $all;
    protected $abos;
    protected $product_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($abos, $product_id, $all)
    {
        $this->facture    = \App::make('App\Droit\Abo\Repo\AboFactureInterface');
        $this->all        = $all;
        $this->abos       = $abos;
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

        // All abonnements for the product
        if(!$this->abos->isEmpty())
        {
            foreach($this->abos as $abonnement)
            {
                // Do we already have a facture in the DB?
                $facture = $this->facture->findByUserAndProduct($abonnement->id,  $this->product_id);

                // If not and the abonnement is an abonne create a facture
                if(!$facture && $abonnement->status == 'abonne')
                {
                    $facture = $this->facture->create([
                        'abo_user_id' => $abonnement->id,
                        'product_id'  => $this->product_id,
                        'created_at'  => date('Y-m-d')
                    ]);
                }

                // If we want all afctures to be remade or made if none exist
                if($this->all)
                {
                    $generator->factureAbo($facture->abonnement, $facture);
                }
                else
                {
                    // does an pdf already exist? if not make one
                    if($facture && !$facture->abo_facture)
                    {
                        $generator->factureAbo($facture->abonnement, $facture);
                    }
                }
            }
        }

    }
}
