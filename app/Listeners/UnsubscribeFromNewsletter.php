<?php

namespace App\Listeners;

use App\Events\SubscriptionRemoveTag;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UnsubscribeFromNewsletter
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
     * @param  SubscriptionRemoveTag  $event
     * @return void
     */
    public function handle(SubscriptionRemoveTag $event)
    {
        $worker = \App::make('App\Droit\Newsletter\Worker\SubscriptionWorkerInterface');

        $adresse = $event->adresse;
        $newsletter_id = $event->newsletter_id;

        // if adresse has email
        if(!empty($adresse->email)){
            // test if exist subscriber
            $subscriber = $worker->exist($adresse->email);
            if($subscriber){
                // unsubscribe
                \Log::info('event unsubscribe newsletter: '.$newsletter_id.' email: '.$subscriber->email.'');
                $worker->unsubscribe($subscriber, [$newsletter_id]);
            }
        }
    }
}
