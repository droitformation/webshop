<?php

namespace App\Listeners;

use App\Jobs\SendOrderConfirmation;
use App\Jobs\NotifyAdminNewOrder;
use App\Events\OrderWasPlaced;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Foundation\Bus\DispatchesJobs;

class EmailOrderConfirmation
{
    use DispatchesJobs;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Handle the event.
     *
     * @param  OrderWasPlaced  $event
     * @return void
     */
    public function handle(OrderWasPlaced $event)
    {
        // Send confirmation of order to user
        $job = (new SendOrderConfirmation($event->order))->delay(30);

        $this->dispatch($job);
        
        // Notifiy admin of new order
        $job = (new NotifyAdminNewOrder($event->order))->delay(30);

        $this->dispatch($job);
        
    }
}
