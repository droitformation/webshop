<?php

namespace App\Listeners;

use App\Events\InscriptionWasRegistered;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Jobs\SendConfirmationInscription;
use App\Jobs\NotifyAdminNewInscription;

class EmailInscriptionConfirmation
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
        // Sen email of confirmation to user
        $job = (new SendConfirmationInscription($event->inscription))->delay(15);

        $this->dispatch($job);

        // Notify admin email of new inscription
        $job = (new NotifyAdminNewInscription($event->inscription))->delay(15);

        $this->dispatch($job);

    }
}
