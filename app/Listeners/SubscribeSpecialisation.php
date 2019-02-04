<?php

namespace App\Listeners;

use App\Events\NewsletterStaticCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SubscribeSpecialisation
{
    protected $import;
    protected $mailjet;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        $this->import = \App::make('App\Droit\Newsletter\Worker\ImportWorker');
        $this->mailjet =  \App::make('App\Droit\Newsletter\Worker\MailjetServiceInterface');
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
