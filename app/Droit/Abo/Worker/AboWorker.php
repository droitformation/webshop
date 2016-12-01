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

    /**
     *  Merging pdfs
     */
    public function merge($files, $name, $abo_id)
    {
        $outputDir =  public_path('files/abos/bound/'.$abo_id);
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