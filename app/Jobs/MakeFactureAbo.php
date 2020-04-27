<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class MakeFactureAbo extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $facture;
    protected $abos;
    protected $product;
    protected $options;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($abos, $product, $options)
    {
        $this->facture       = \App::make('App\Droit\Abo\Repo\AboFactureInterface');
        $this->abos          = $abos;
        $this->product       = $product;
        $this->options       = $options;

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
        $repo    = \App::make('App\Droit\Abo\Repo\AboFactureInterface');

        $print = $this->options['print'] ?? null;
        $date  = $this->options['date'] ?? \Carbon\Carbon::today()->toDateString();
        $all   = $this->options['all'] ?? null;

        // All abonnements for the product
        if(!$this->abos->isEmpty()) {
            foreach($this->abos as $abonnement) {
                if(!$abonnement->deleted_at){
                    // Do we already have a facture in the DB?
                    $facture = $repo->findByUserAndProduct($abonnement->id,  $this->product->id);

                    // If not and the abonnement is an abonne create a facture
                    if(!$facture && ($abonnement->status == 'abonne' || $abonnement->status == 'tiers'))
                    {
                        $facture = $this->facture->create([
                            'abo_user_id' => $abonnement->id,
                            'product_id'  => $this->product->id,
                            'created_at'  => $date
                        ]);
                    }

                    $facture = $repo->update(['id' => $facture->id, 'created_at' => $date]);

                    // If we want all factures to be remade or made if none exist
                    // does an pdf already exist? if not make one
                    // All is for the controller otherwise for sending
                    if($all || ($facture && !$facture->doc_facture)) {

                        if($facture->doc_facture){ \File::delete(public_path($facture->doc_facture)); }

                        if($print){
                            $generator->setPrint(true);
                        }

                        $generator->makeAbo('facture', $facture);
                    }
                }
            }
        }

    }
}
