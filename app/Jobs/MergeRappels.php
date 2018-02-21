<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class MergeRappels extends Job implements ShouldQueue
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
        $worker  = \App::make('App\Droit\Abo\Worker\AboWorkerInterface');
        $facture = \App::make('App\Droit\Abo\Repo\AboFactureInterface');

        $factures = $facture->getRappels($this->product->id);

        if(!$factures->isEmpty())
        {
            // Directory for edition => product_id
            $dir = 'files/abos/rappel/'.$this->product->id;
            
            // Get all files in directory
            $lists = $factures->map(function ($item, $key)
            {
                $pdf = '';
                $rappel = $item->rappels->sortByDesc('created_at')->first();
                if($rappel){
                    $pdf = 'files/abos/rappel/'.$this->product->id.'/rappel_'.$rappel->id.'_'.$item->id.'.pdf';
                }
                if(\File::exists(public_path($pdf))){ return public_path($pdf); }
            })->all();

            // Get only the last rappels from directory
            $files = \File::files(public_path($dir));
            $files = array_intersect($lists,$files);

            $name  = 'rappels_'.$this->product->reference.'_'.$this->product->edition_clean;

            // Merge the files
            if(!empty($files))
            {
                $worker->merge($files, $name, $this->abo->id);
            }
        }
    }
}
