<?php

namespace App\Listeners;

use App\Events\InscriptionWasCreated;
use App\Jobs\MakeDocument;
use Illuminate\Foundation\Bus\DispatchesJobs;

class CreateDocumentsInscription
{
    use DispatchesJobs;

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
     * @param  InscriptionWasCreated  $event
     * @return void
     */
    public function handle(InscriptionWasCreated $event)
    {
        $job = (new MakeDocument($event->inscription));

        $this->dispatch($job);
    }
}
