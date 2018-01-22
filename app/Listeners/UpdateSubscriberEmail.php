<?php

namespace App\Listeners;

use App\Events\SubscriberEmailUpdated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateSubscriberEmail
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
     * @param  SubscriberEmailUpdated  $event
     * @return void
     */
    public function handle(SubscriberEmailUpdated $event)
    {
        $worker = \App::make('App\Droit\Newsletter\Worker\SubscriptionWorkerInterface');
        $repo   = \App::make('App\Droit\Newsletter\Repo\NewsletterUserInterface');

        $email_old = $event->email_old;
        $email_new = $event->email_new;

        $subscriber = $worker->exist($email_old);

        if($subscriber){
            $has = $subscriber->subscriptions->pluck('id')->all();

            // unsubscribe all and delete
            $worker->unsubscribe($subscriber,$has);

            // make new subscriber and add all
            $new = $repo->create(['email' => $email_new, 'activated_at' => \Carbon\Carbon::now(), 'activation_token' => md5($email_new.\Carbon\Carbon::now())]);

            $worker->subscribe($new,$has);
        }
    }
}
