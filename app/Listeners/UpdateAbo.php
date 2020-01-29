<?php

namespace App\Listeners;

use App\Events\AdresseTiersDeletedAbo;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateAbo
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
     * @param  AdresseTiersDeletedAbo  $event
     * @return void
     */
    public function handle(AdresseTiersDeletedAbo $event)
    {
        $repo = \App::make('App\Droit\Abo\Repo\AboUserEloquent');
        $repo->changeStatus(['id' => $event->abo_id, 'status' => 'abonne']);
    }
}
