<?php

namespace App\Droit\Abo\Worker;

use App\Droit\Abo\Worker\AboRappelWorkerInterface;

use App\Droit\Abo\Repo\AboFactureInterface;
use App\Droit\Generate\Pdf\PdfGeneratorInterface;

use App\Jobs\MergeRappels;
use App\Jobs\MakeRappelAbo;
use App\Jobs\NotifyJobFinished;
use App\Droit\Abo\Repo\AboRappelInterface;
use Illuminate\Foundation\Bus\DispatchesJobs;

class AboRappelWorker implements AboRappelWorkerInterface{

    use DispatchesJobs;

    protected $facture;
    protected $rappel;
    protected $generator;

    public function __construct(AboFactureInterface $facture, PdfGeneratorInterface $generator, AboRappelInterface $rappel)
    {
        $this->facture    = $facture;
        $this->rappel     = $rappel;
        $this->generator  = $generator;

        setlocale(LC_ALL, 'fr_FR.UTF-8');
    }

    public function generate($product, $abo, $options)
    {
        $factures = $this->facture->getRappels($product->id);

        if(!$factures->isEmpty()) {

            foreach ($factures as $facture){
                // Make the rappels
                $job = (new MakeRappelAbo($facture, $options));
                $this->dispatch($job);
            }

            $product = $factures->first()->product;
            
            // Job for merging documents
            $merge = (new MergeRappels($product, $abo));
            $this->dispatch($merge);

            $job = (new NotifyJobFinished('Les rappels ont été crées et attachés. Nom du fichier: <strong>factures_'.$product->reference.'_'.$product->edition_clean.'</strong>'));
            $this->dispatch($job);

        }
    }
    
    public function make($rappel, $new = false, $print = null)
    {
        // get facture
        $facture = $rappel->facture;

        // what nth rappel
        $nbr = $facture->rappels->isEmpty() ? 1 : $facture->rappels->count();

        if($facture->rappels->isEmpty() || $new)
        {
            if($print){
                $this->generator->setPrint(true);
            }

            $this->generator->makeAbo('rappel', $facture, $nbr, $rappel);
        }
    }

    public function makeRappel($facture, $new = null, $print = null)
    {
        if($facture->rappels->isEmpty() || $new) {
            $nbr = $facture->rappels->isEmpty() ? 1 : $facture->rappels->count() + 1;
            $rappel = $this->rappel->create(['abo_facture_id' => $facture->id]);
        }
        else {
            $nbr    = $facture->rappels->count();
            $rappel = $facture->rappels->sortBy('created_at')->last();
        }

        $this->generator->setPrint($print ?? false);
        $this->generator->makeAbo('rappel', $facture, $nbr, $rappel);
    }

    /*
   * Generate all invoices
   * */
    public function bind($product,$abo)
    {
        // Job for merging documents
        $merge = (new MergeRappels($product, $abo));
        $this->dispatch($merge);

        $job = (new NotifyJobFinished('Les rappels ont été attachés. Nom du fichier: <strong>'.'rappels_'.$product->reference.'_'.$product->edition.'</strong>'));
        $this->dispatch($job);

        flash('Les rappels sont re-attachés.<br/>Rafraichissez la page pour mettre à jour le document.')->success();

        return redirect()->back();
    }
}