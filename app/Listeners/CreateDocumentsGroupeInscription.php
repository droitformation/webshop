<?php

namespace App\Listeners;

use App\Events\GroupeInscriptionWasRegistered;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Jobs\MakeDocumentGroupe;

class CreateDocumentsGroupeInscription
{
    use DispatchesJobs;

    /**
     * Handle the event.
     *
     * @param  GroupeInscriptionWasCreated  $event
     * @return void
     */
    public function handle(GroupeInscriptionWasRegistered $event)
    {
        $job = (new MakeDocumentGroupe($event->groupe));

        $this->dispatch($job);
    }
}
