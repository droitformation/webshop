<?php

namespace App\Listeners;

use App\Events\SubscriptionAddTag;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SubscribeToNewsletter
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
     * @param  SubscriptionAddTag  $event
     * @return void
     */
    public function handle(SubscriptionAddTag $event)
    {
        $worker = \App::make('App\Droit\Newsletter\Worker\SubscriptionWorkerInterface');

        $adresse = $event->adresse;
        $newsletter_id = $event->newsletter_id;

        // if adresse has email
        if(!empty($adresse->email)){
            // test if exist subscriber
            $subscriber = $worker->exist($adresse->email);
            // if not exist create
            if(!$subscriber){
                $subscriber = $worker->make($adresse->email,$newsletter_id);
            }

            // subscribe
            \Log::info('event newsletter: '.$newsletter_id.' email: '.$subscriber->email.'');
            $worker->subscribe($subscriber, [$newsletter_id]);
        }
    }
}
