<?php

namespace App\Listeners;

use App\Events\InscriptionWasCreated;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Jobs\MakeDocument;

class CreateDocumentsInscription
{
    use DispatchesJobs;

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
