<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ExportBatchAdresse extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $path;
    protected $adresses;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($path, $adresses)
    {
        $this->path     = $path;
        $this->adresses = $adresses;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        \Excel::load($this->path, function($excel){
            $excel->sheet('Export_Adresses', function ($sheet){

                $converted = $this->prepareAdresse($this->adresses);
                $sheet->rows($converted->toArray());

            });
        })->store('xls', storage_path('excel/exports'));
        
        return true;
    }

    private function prepareAdresse($adresses)
    {
        $columns = collect(array_keys(config('columns.names')));

        return $adresses->map(function ($adresse) use ($columns) {
            return $columns->map(function ($column) use ($adresse){
                return $adresse->$column;
            });
        });
    }
}
