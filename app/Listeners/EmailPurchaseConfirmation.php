<?php

namespace App\Listeners;

use App\Jobs\SendConfirmationEmail;
use App\Jobs\CreateOrderInvoice;
use App\Events\OrderWasPlaced;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Foundation\Bus\DispatchesJobs;

class EmailPurchaseConfirmation
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
        $order = $event->order;
        $job   = (new SendConfirmationEmail($order))->delay(120);

        $this->dispatch($job);

    }
}
