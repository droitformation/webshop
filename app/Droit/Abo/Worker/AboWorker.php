<?php

namespace App\Droit\Abo\Worker;

use App\Droit\Abo\Worker\AboWorkerInterface;
use App\Droit\Abo\Repo\AboFactureInterface;
use App\Droit\Abo\Repo\AboRappelInterface;
use App\Droit\Abo\Repo\AboUserInterface;
use App\Droit\Abo\Repo\AboInterface;
use App\Droit\Generate\Pdf\PdfGeneratorInterface;
use App\Jobs\MakeFactureAbo;
use App\Jobs\MergeFactures;
use App\Jobs\MergeRappels;
use App\Jobs\MakeRappelAbo;
use App\Jobs\NotifyJobFinished;
use Symfony\Component\Process\Process;
use Illuminate\Foundation\Bus\DispatchesJobs;

class AboWorker implements AboWorkerInterface{

    use DispatchesJobs;

    protected $facture;
    protected $rappel;
    protected $abo;
    protected $abonnement;
    protected $generator;

    public function __construct(AboFactureInterface $facture, AboRappelInterface $rappel, PdfGeneratorInterface $generator, AboUserInterface $abonnement, AboInterface $abo)
    {
        $this->facture    = $facture;
        $this->rappel     = $rappel;
        $this->abo        = $abo;
        $this->abonnement = $abonnement;
        $this->generator  = $generator;

        setlocale(LC_ALL, 'fr_FR.UTF-8');
    }

    public function rappels($product_id, $abo_id)
    {
        $factures = $this->facture->findByProduct($product_id);

        if(!$factures->isEmpty())
        {
            $chunks = $factures->chunk(20);

            foreach($chunks as $chunk)
            {
                $job = (new MakeRappelAbo($chunk, $product_id));
                $this->dispatch($job);
            }

            $product = $factures->first()->product;

            // Name of the pdf file with all the invoices bound together for a particular edition
            $name = 'rappels_'.$product->reference.'_'.$product->edition;

            // Job for merging documents
            $merge = (new MergeRappels($product->id, $name, $abo_id));
            $this->dispatch($merge);

            $job = (new NotifyJobFinished('Les rappels ont été crées et attachés. Nom du fichier: <strong>'.$name.'</strong>'));
            $this->dispatch($job);
        }
    }

    public function generate($abo, $product_id, $all = false)
    {
        // All abonnements for the product
        if(!$abo->abonnements->isEmpty())
        {
            $abonnes = $abo->abonnements->whereIn('status',['abonne']);
            $chunks  = $abonnes->chunk(20);

            foreach($chunks as $chunk)
            {
                $job = (new MakeFactureAbo($chunk, $product_id, $all));
                $this->dispatch($job);
            }

            $product = $abo->products->whereLoose('id', $product_id);
            $product = !$product->isEmpty() ? $product->first() : null;

            if($product)
            {
                // Name of the pdf file with all the invoices bound together for a particular edition
                $name = 'factures_'.$product->reference.'_'.$product->edition;

                // Job for merging documents
                $merge = (new MergeFactures($product->id, $name, $abo->id));
                $this->dispatch($merge);

                $job = (new NotifyJobFinished('Les factures ont été crées et attachés. Nom du fichier: '.$name));
                $this->dispatch($job);
            }
        }
    }

    public function make($facture_id, $rappel = false)
    {
        $rappels = null;

        $facture = $this->facture->find($facture_id);

        if($rappel)
        {
            $rappels = $this->rappel->findByFacture($facture_id);
            $rappels = $rappels->count();
        }

        $this->generator->makeAbo('facture', $facture, $rappels, $rappel);
    }

    /**
     *  Merging pdfs
     */
    public function merge($files, $name, $abo_id)
    {
        $outputDir =  public_path('/files/abos/bound/'.$abo_id);
        $outputName = $outputDir.'/'.$name.'.pdf';

        if (!\File::exists($outputDir))
        {
            \File::makeDirectory($outputDir);
        }

        if (!\File::exists($outputName))
        {
            \File::delete($outputName);
        }

        $pdf = new \Clegginabox\PDFMerger\PDFMerger;

        foreach($files as $file)
        {
            $pdf->addPDF($file, 'all');
        }

        $pdf->merge('file', $outputName, 'P');
    }

    public function update($abonnement)
    {
        $factures = $abonnement->factures;

        if(!$factures->isEmpty())
        {
            foreach($factures as $facture)
            {
                if($abonnement->status == 'abonne')
                {
                    $this->generator->makeAbo('facture', $facture);
                }
                else
                {
                     if (!\File::exists($facture->abo_facture))
                     {
                         \File::delete($facture->abo_facture);
                     }
                }
            }
        }
    }

    /**
     * Make new abonnement for client
     *
     * @param  array
     * @return void
     */
    public function makeAbonnement($data)
    {
        $collection = new \Illuminate\Support\Collection();

        foreach($data as $item)
        {
            // find abo and max number
            $max = $this->abonnement->max($item['abo_id']) + 1;

            // Create new abonnement
            $abonnement = $this->abonnement->create($item + ['numero' => $max]);

            // Create first invoice
            $facture = $this->abonnement->makeFacture(['abo_user_id' => $abonnement->id, 'product_id' => $item['product_id']]);

            // Generate first pdf invoice
            $this->make($facture->id);

            $collection->push($abonnement);
        }

        return $collection;
    }
}