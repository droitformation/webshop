<?php

namespace App\Listeners;

use App\Events\EmailAccountUpdated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateEmailAccount
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
     * @param  EmailAccountUpdated  $event
     * @return void
     */
    public function handle(EmailAccountUpdated $event)
    {
        $model = $event->model;
        $email = $event->email;

        if($model instanceof \App\Droit\User\Entities\User){

            // Get only contact adresses
            $adresses = $model->adresses->where('type',1);
            $adresses->each(function ($adresse, $key) use ($email) {
                if($adresse->email != $email){
                    // keep old email to update subscriptions
                    $old_email = $adresse->email;

                    $adresse->email = $email;
                    $adresse->save();

                    event(new \App\Events\SubscriberEmailUpdated($old_email,$email));
                }
            });
        }

        if($model instanceof \App\Droit\Adresse\Entities\Adresse){
            if(isset($model->user) &&  $model->user->email != $email){
                // keep old email to update subscriptions
                $old_email = $model->user->email;

                $user = $model->user;
                $user->email = $email;
                $user->save();

                event(new \App\Events\SubscriberEmailUpdated($old_email,$email));
            }
        }
    }
}
