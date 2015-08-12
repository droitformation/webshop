<?php

namespace App\Listeners;

use App\Events\InscriptionWasRegistered;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Jobs\SendConfirmationInscriptionEmail;

class EmailRegisterInscriptionConfirmation
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
    public function handle(InscriptionWasRegistered $event)
    {

        $job = (new SendConfirmationInscriptionEmail($event->inscription))->delay(5);

        $this->dispatch($job);

    }
}
