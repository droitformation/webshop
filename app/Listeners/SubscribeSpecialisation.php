<?php

namespace App\Listeners;

use App\Events\NewsletterStaticCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Droit\Newsletter\Worker\ImportWorker;
use App\Droit\Newsletter\Worker\MailjetServiceInterface;

class SubscribeSpecialisation
{
    protected $import;
    protected $mailjet;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(ImportWorker $import, MailjetServiceInterface $mailjet)
    {
        $this->import  = $import;
        $this->mailjet = $mailjet;
    }

    /**
     * Handle the event.
     *
     * @param  NewsletterStaticCreated  $event
     * @return void
     */
    public function handle(NewsletterStaticCreated $event)
    {
        $newsletter = $event->newsletter;
        $name = $event->name;

        $id = $this->mailjet->createList($name);

        $newsletter->list_id = $id;
        $newsletter->save();

        $this->import->syncSpecialisations($newsletter->id);
    }
}
