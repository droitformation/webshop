<?php

namespace App\Droit\Abo\Worker;

use App\Droit\Abo\Worker\AboRappelWorkerInterface;

use App\Droit\Abo\Repo\AboFactureInterface;
use App\Droit\Generate\Pdf\PdfGeneratorInterface;

use App\Jobs\MergeRappels;
use App\Jobs\MakeRappelAbo;
use App\Jobs\NotifyJobFinished;

use Illuminate\Foundation\Bus\DispatchesJobs;

class AboRappelWorker implements AboRappelWorkerInterface{

    use DispatchesJobs;

    protected $facture;
    protected $generator;

    public function __construct(AboFactureInterface $facture, PdfGeneratorInterface $generator)
    {
        $this->facture    = $facture;
        $this->generator  = $generator;

        setlocale(LC_ALL, 'fr_FR.UTF-8');
    }

    public function generate($product, $abo)
    {
        $factures = $this->facture->getRappels($product->id);

        if(!$factures->isEmpty())
        {
            // Get chunk
            $chunks = $factures->chunk(15);

            foreach($chunks as $chunk) {
                $job = (new MakeRappelAbo($chunk));
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
    
    public function make($rappel, $new = false)
    {
        // get facture
        $facture = $rappel->facture;

        // what nth rappel
        $nbr = $facture->rappels->isEmpty() ? 1 : $facture->rappels->count();

        if($facture->rappels->isEmpty() || $new)
        {
            $this->generator->makeAbo('rappel', $facture, $nbr, $rappel);
        }
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

        alert()->success('Les rappels sont re-attachés.<br/>Rafraichissez la page pour mettre à jour le document.');

        return redirect()->back();
    }
}