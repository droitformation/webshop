<?php

namespace App\Listeners;

use App\Events\OrderWasPlaced;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Mail\Mailer;

class EmailPurchaseConfirmation
{

    protected $mailer;

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
        $order->load('user');
        $user  = $order->user;

        $this->mailer->send('emails.shop.confirmation', ['user' => $user], function ($m) use ($user) {
            $m->to($user->email, $user->name)->subject('Confirmation de commande');
        });

    }
}
