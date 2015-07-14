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

        setlocale(LC_ALL, 'fr_FR.UTF-8');
        $date   = \Carbon\Carbon::now()->formatLocalized('%d %B %Y');
        $title  = 'Votre commande sur publications-droit.ch';
        $logo   = 'facdroit.png';

        $order = $event->order;
        $order->load('products','user','shipping','payement');

        $user     = $order->user;
        $duDate   = $order->created_at->addDays(30)->formatLocalized('%d %B %Y');
        $products = $order->products->groupBy('id');

        $data = [
            'title'     => $title,
            'logo'      => $logo,
            'order'     => $order,
            'products'  => $products,
            'date'      => $date,
            'duDate'    => $duDate
        ];

        $facture = 'files/shop/factures/facture_'.$order->order_no.'.pdf';

        $this->mailer->send('emails.shop.confirmation', $data , function ($m) use ($user,$facture) {

            $m->to($user->email, $user->name)->subject('Confirmation de commande');
            $m->attach($facture);

        });
    }
}
