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
    protected $status_files;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($product, $abo, $status_files)
    {
        $this->product = $product;
        $this->abo     = $abo;
        $this->status_files  = $status_files;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $worker = \App::make('App\Droit\Abo\Worker\AboWorkerInterface');

        // Make each types
        if(!$this->status_files->isEmpty()){
            foreach ($this->status_files as $status => $abos){

                // sort keys
                $all = $abos->toArray();
                ksort($all);

                $name  = 'factures_'.$status.'_'.$this->product->reference.'_'.$this->product->edition_clean;
                $worker->merge($all, $name, $this->abo->id);
            }
        }

    }
}
