<?php

namespace App\Droit\Abo\Worker;

use App\Droit\Abo\Worker\AboWorkerInterface;
use App\Droit\Abo\Repo\AboFactureInterface;
use App\Droit\Abo\Repo\AboRappelInterface;
use App\Droit\Abo\Repo\AboUserInterface;
use App\Droit\Generate\Pdf\PdfGeneratorInterface;

use Symfony\Component\Process\Process;

class AboWorker implements AboWorkerInterface{

    protected $facture;
    protected $rappel;
    protected $abo;
    protected $generator;

    public function __construct(AboFactureInterface $facture, AboRappelInterface $rappel, PdfGeneratorInterface $generator, AboUserInterface $abo)
    {
        $this->facture   = $facture;
        $this->rappel    = $rappel;
        $this->abo       = $abo;
        $this->generator = $generator;

        setlocale(LC_ALL, 'fr_FR.UTF-8');
    }

    public function make($facture_id, $rappel = false)
    {
        $rappels = null;

        $facture = $this->facture->find($facture_id);
        $abo     = $this->abo->find($facture->abo_user_id);

        if($rappel)
        {
            $rappels = $this->rappel->findByFacture($facture_id);
            $rappels = $rappels->count();
        }

        $this->generator->factureAbo($abo ,$facture, $rappels);
    }

    /**
     *  Merging pdfs
     */
    public function merge($files, $name, $abo_id)
    {
        $outputDir =  public_path().'/files/abos/bound/'.$abo_id.'/';
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
                    $this->generator->factureAbo($abonnement ,$facture);
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
}