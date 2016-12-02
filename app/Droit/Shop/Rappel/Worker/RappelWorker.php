<?php

namespace App\Droit\Abo\Worker;

use App\Droit\Shop\Rappel\Worker\RappelWorkerInterface;

use App\Droit\Shop\Rappel\Repo\RappelInterface;
use App\Droit\Shop\Order\Repo\OrderInterface;
use App\Droit\Generate\Pdf\PdfGeneratorInterface;

use Illuminate\Foundation\Bus\DispatchesJobs;

class RappelWorker implements RappelWorkerInterface{

    use DispatchesJobs;

    protected $order;
    protected $rappel;
    protected $generator;

    public function __construct(RappelInterface $rappel, OrderInterface $order, PdfGeneratorInterface $generator)
    {
        $this->rappel     = $rappel;
        $this->order      = $order;
        $this->generator  = $generator;
        
        setlocale(LC_ALL, 'fr_FR.UTF-8');
    }

    public function generate($order)
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
    
}