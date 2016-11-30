<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class MergeRappels extends Job implements ShouldQueue
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
        $product_id = $this->product_id;

        $worker  = \App::make('App\Droit\Abo\Worker\AboWorkerInterface');
        $facture = \App::make('App\Droit\Abo\Repo\AboFactureInterface');

        $factures = $facture->getRappels($product_id);

        if(!$factures->isEmpty())
        {
            // Directory for edition => product_id
            $dir = 'files/abos/rappel/'.$product_id;

            // Get all files in directory
            $lists = $factures->map(function ($item, $key) use ($product_id)
            {
                $rappel = $item->rappels->sortByDesc('created_at')->first();
                $pdf    = 'files/abos/rappel/'.$product_id.'/rappel_'.$rappel->id.'_'.$rappel->abo_facture_id.'.pdf';

                if(\File::exists(public_path($pdf))){ return public_path($pdf); }
            })->all();

            // Get only the last rappels from directory
            $files = \File::files(public_path($dir));
            $files = array_intersect($lists,$files);

            if(!empty($files))
            {
                $worker->merge($files, $this->name, $this->abo_id);
            }
        }
    }
}
