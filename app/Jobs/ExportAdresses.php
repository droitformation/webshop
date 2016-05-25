<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ExportAdresses extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $path;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        \Excel::load($this->path, function($excel) {
            $excel->sheet('Export_Adresses', function ($sheet) {
                $sheet->prependRow(array_values(config('columns.names')));
                $sheet->row(1, function($row) {
                    $row->setFontWeight('bold')->setFontSize(14);
                });
            });
        })->store('xls', storage_path('excel/exports'));

        return true;
    }
}
