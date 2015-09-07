<?php

namespace App\Listeners;

use App\Events\GroupeInscriptionWasRegistered;
use App\Jobs\MakeGroupeDocument;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\DispatchesJobs;

class CreateDocumentsGroupeInscription
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
     * @param  GroupeInscriptionWasCreated  $event
     * @return void
     */
    public function handle(GroupeInscriptionWasRegistered $event)
    {
        $job = (new MakeGroupeDocument($event->groupe));

        $this->dispatch($job);
    }
}
