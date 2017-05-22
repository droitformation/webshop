<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class MergeFactures extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $product;
    protected $abo;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($product, $abo)
    {
        $this->product = $product;
        $this->abo     = $abo;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
       // ini_set('memory_limit', '512M');

        $datadir = 'files/abos/facture/'.$this->product->id;
        $files   = \File::files(public_path($datadir));

        $outputDir =  public_path('files/abos/bound/'.$this->abo->id);
        $name = 'factures_'.$this->product->reference.'_'.$this->product->edition_clean.'.pdf';
        $outputName = $outputDir.'/'.$name.'.pdf';

        if(!empty($files)) {
            $cmd = "gs -q -dNOPAUSE -dBATCH -sDEVICE=pdfwrite -sOutputFile=$outputName ";

            //Every pdf file should come at the end of the command
            foreach($files as $file) {
                $cmd .= $file." ";
            }

            $result = shell_exec($cmd);
        }

    }
}
