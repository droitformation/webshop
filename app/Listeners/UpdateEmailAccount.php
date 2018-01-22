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
            $adresses->each(function ($adresse, $key) use($email) {
                if($adresse->email != $email){
                    $adresse->email = $email;
                    $adresse->save();
                }
            });
        }

        if($model instanceof \App\Droit\Adresse\Entities\Adresse){
            if(isset($model->user) &&  $model->user->email != $email){
                $user = $model->user;
                $user->email = $email;
                $user->save();
            }
        }
    }
}
