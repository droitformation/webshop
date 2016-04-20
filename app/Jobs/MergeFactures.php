<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class MergeFactures extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $product_id;
    protected $name;
    protected $abo_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($product_id, $name, $abo_id)
    {
        $this->product_id = $product_id;
        $this->name       = $name;
        $this->abo_id     = $abo_id;
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
        $dir   = 'files/abos/facture/'.$this->product_id;

        // Get all files in directory
        $files = \File::files(public_path($dir));

        if(!empty($files))
        {
            $worker->merge($files, $this->name, $this->abo_id);
        }

    }
}
