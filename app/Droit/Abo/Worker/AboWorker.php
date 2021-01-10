<?php

namespace App\Droit\Abo\Worker;

use App\Droit\Abo\Repo\AboFactureInterface;
use App\Droit\Abo\Repo\AboRappelInterface;
use App\Droit\Abo\Repo\AboUserInterface;
use App\Droit\Abo\Repo\AboInterface;
use App\Droit\Generate\Pdf\PdfGeneratorInterface;
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
        // Prepare names output directory and output filename
        $outputDir =  public_path('files/abos/bound/'.$abo_id);
        $outputName = $outputDir.'/'.$name.'.pdf';

        if(\File::exists($outputName)){
            $outputName = $outputDir.'/'.$name.'_'.date('mY').'.pdf';
        }

        // Create output directory if doesn't exist. Delete output file if exist
        if (!\File::exists($outputDir)) { \File::makeDirectory($outputDir); }
        if (\File::exists($outputName)) { \File::delete($outputName); }

        // create command if we have files
        if(!empty($files)) {

            $gs = (\App::environment() == 'local' ? '/usr/local/bin/gs' : 'gs');

            $cmd = $gs." -q -dNOPAUSE -dBATCH -sDEVICE=pdfwrite -sOutputFile=$outputName ";

            //Every pdf file should come at the end of the command
            foreach($files as $file) {
                $file = new \SplFileInfo($file);
                $cmd .= $file." ";
            }

            $cmd .= " 2>&1";

            \Log::info($cmd);

            $result = shell_exec($cmd);

            \Log::info(serialize($result));
        }
    }

    /**
     * Make new abonnement for client from frontend shop
     *
     * @param  array
     * @return void
     */
    public function makeAbonnement($data)
    {
        $collection = new \Illuminate\Support\Collection();

        foreach($data as $item) {
            // find abo and max number
            $max = $this->abonnement->max($item['abo_id']) + 1;

            // Create new abonnement
            $abonnement = $this->abonnement->create($item + ['numero' => $max]);

            // Mark reference if any
            $reference = \App\Droit\Transaction\Reference::make($abonnement);

            // Create first invoice
            $facture = $this->abonnement->makeFacture(['abo_user_id' => $abonnement->id, 'product_id' => $item['product_id']]);

            // Generate first pdf invoice
            $this->generator->setPrint(true);
            $this->generator->makeAbo('facture', $facture);

            $collection->push($abonnement);
        }

        return $collection;
    }

}