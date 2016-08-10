<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Mail\Mailer;
use \Illuminate\Support\Collection;

class SendConfirmationAbo extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $abos;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Collection $abos)
    {
       $this->abos = $abos;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        setlocale(LC_ALL, 'fr_FR.UTF-8');
    }
}
