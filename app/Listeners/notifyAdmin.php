<?php

namespace App\Listeners;

use App\Events\jobFinsished;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class notifyAdmin implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  jobFinsished  $event
     * @return void
     */
    public function handle(jobFinsished $event)
    {
        //
    }
}
