<?php

namespace App\Listeners;

use App\Events\GroupeInscriptionWasRegistered;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Jobs\MakeDocumentGroupe;
use Illuminate\Contracts\Mail\Mailer;

class CreateDocumentsGroupeInscription
{
    use DispatchesJobs;

    protected $mailer;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

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
