<?php

namespace App\Listeners;

use App\Events\NewAboRequest;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Jobs\SendAboConfirmation;
use App\Jobs\NotifyAdminNewAbo;

class EmailAboConfirmation
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
     * @param  NewAboRequest  $event
     * @return void
     */
    public function handle(NewAboRequest $event)
    {
        // Send email of confirmation to user
        $job = (new SendAboConfirmation($event->abos))->delay(15);

        $this->dispatch($job);

        // Notify admin email of new inscription
        $job = (new NotifyAdminNewAbo($event->abos))->delay(15);

        $this->dispatch($job);
    }
}