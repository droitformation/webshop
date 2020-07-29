<?php

namespace App\Listeners;

use App\Events\PriceLinkCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class MakeFreePrice
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
     * @param  PriceLinkCreated  $event
     * @return void
     */
    public function handle(PriceLinkCreated $event)
    {
        //
    }
}
