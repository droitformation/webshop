<?php

namespace App\Listeners;

use App\Events\NewsletterStaticCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SubscribeSpecialisation
{
    protected $import;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        $this->import = \App::make('App\Droit\Newsletter\Worker\ImportWorker');
    }

    /**
     * Handle the event.
     *
     * @param  NewsletterStaticCreated  $event
     * @return void
     */
    public function handle(NewsletterStaticCreated $event)
    {
        $newsletter_id = $event->newsletter_id;

        $this->import->syncSpecialisations($newsletter_id);
    }
}
