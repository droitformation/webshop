<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Mail\Mailer;
use App\Droit\Shop\Order\Entities\Order;

class SendConfirmationEmail extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $order;
    protected $mailer;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order  = $order;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Mailer $mailer)
    {
        setlocale(LC_ALL, 'fr_FR.UTF-8');

        $date   = \Carbon\Carbon::now()->formatLocalized('%d %B %Y');
        $title  = 'Votre commande sur publications-droit.ch';
        $logo   = 'facdroit.png';

        $order = $this->order;
        $order->load('products','shipping','payement');

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

        $facture = public_path().'/files/shop/factures/facture_'.$order->order_no.'.pdf';
        $name    = 'facture_'.$order->order_no.'.pdf';

        $mailer->send('emails.shop.confirmation', $data , function ($message) use ($user,$facture,$name) {
            $message->to($user->email, $user->name)->subject('Confirmation de commande');

            if($facture)
            {
                $message->attach($facture, array('as' => $name, 'mime' => 'application/pdf'));
            }

        });
    }
}
