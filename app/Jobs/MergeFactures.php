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
        $worker = \App::make('App\Droit\Abo\Worker\AboWorkerInterface');

        // Directory for edition => product_id
        $dir   = 'files/abos/facture/'.$this->product->id;
        $name  = 'factures_'.$this->product->reference.'_'.$this->product->edition_clean;

        // Get all files in directory
        $files = \File::files(public_path($dir));

        if(!empty($files))
        {
            $worker->merge($files, $name, $this->abo->id);
        }
    }
}
