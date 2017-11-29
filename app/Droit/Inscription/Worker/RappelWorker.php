<?php

namespace App\Droit\Inscription\Worker;

use App\Droit\Inscription\Worker\RappelWorkerInterface;
use App\Droit\Inscription\Repo\RappelInterface;

class RappelWorker implements RappelWorkerInterface
{
    protected $rappel;
    protected $generator;

    public function __construct(RappelInterface $rappel)
    {
        $this->rappel    = $rappel;
        $this->generator = \App::make('App\Droit\Generate\Pdf\PdfGeneratorInterface');
    }

    public function generateSimple($inscription, $print = false)
    {
        $rappel = $this->rappel->create([
            'colloque_id'    => $inscription->colloque_id,
            'inscription_id' => $inscription->id,
            'user_id'        => $inscription->user_id,
            'group_id'       => $inscription->group_id
        ]);

        $this->generator->setPrint($print);
        $this->generator->make('facture', $inscription, $rappel);

        return $rappel;
    }

    public function generateMultiple($group, $print = false)
    {
        $rappel = $this->rappel->create([
            'colloque_id'    => $group->colloque_id,
            'inscription_id' => null,
            'user_id'        => $group->user_id,
            'group_id'       => $group->id
        ]);

        $this->generator->setPrint($print);
        $this->generator->make('facture', $group, $rappel);

        return $rappel;
    }

    public function generate($inscription)
    {
        if (isset($inscription->groupe)) {
            $this->generateMultiple($inscription->groupe);
        } else {
            $this->generateSimple($inscription);
        }
    }

    public function make($inscriptions, $makemore = false)
    {
        if(!$inscriptions->isEmpty()) {
            foreach ($inscriptions as $inscription) {

                $rappel = $inscription->list_rappel->sortBy('created_at')->last();
                
                if($makemore || !$rappel){
                    $this->generate($inscription);
                }
            }
        }
    }

    public function generateWithBv($inscriptions){

        if(!$inscriptions->isEmpty())
        {
            $dir_temp   = public_path('files/colloques/temp');
            $dir_output = public_path('files/colloques/rappel');

            // Make sur the temp folder exist
            if(!\File::exists($dir_temp)){ mkdir($dir_temp); }

            $this->make($inscriptions);

            // Get all files in directory
            $inscriptions->map(function ($inscription, $key) use ($dir_temp) {
                $model  = isset($inscription->groupe) ? $inscription->groupe : $inscription;

                if($model->list_rappel){
                    $rappel = $model->list_rappel->sortBy('created_at')->last();

                    $path = 'rappel_'.$rappel->id.'_'.$rappel->colloque_id.'.pdf';

                    $this->generator->toPrint = true;
                    $this->generator->makeInscriptionRappel($model, $rappel, $dir_temp.'/'.$path);
                }
            });

            sleep(3);

            // Get only the last rappels from directory
            $files = \File::files($dir_temp);

            // Merge the files
            if(!empty($files)) {
                return $this->merge($files, $dir_output.'/rappels_colloque_'.$inscriptions->first()->colloque_id.'.pdf');
            }
        }
    }

    /**
     *  Merging pdfs
     */
    public function merge($files, $name)
    {
        // Prepare names output directory and output filename
        // Create output directory if doesn't exist. Delete output file if exist
        if (\File::exists($name)) { \File::delete($name); }

        $pdf = new \PDFMerger;

        // create command if we have files
        if(!empty($files)) {
            foreach ($files as $file){
                $pdf->addPDF($file, 'all');
            }

            $pdf->merge('file', $name);

            sleep(1);
            emptyDirectory(public_path('files/colloques/temp'));

            return $name;
        }
    }
}