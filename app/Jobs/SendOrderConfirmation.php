<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Mail\Mailer;
use App\Droit\Shop\Order\Entities\Order;

class SendOrderConfirmation extends Job implements ShouldQueue
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

        setlocale(LC_ALL, 'fr_FR.UTF-8');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Mailer $mailer)
    {
        // Load infos
        $this->order->load('products','shipping','payement','user');

        $user = $this->order->user;

        $data = [
            'title'     => 'Votre commande sur publications-droit.ch',
            'concerne'  => 'Votre commande',
            'logo'      => 'facdroit.png',
            'order'     => $this->order,
            'products'  => $this->order->products->groupBy('id'),
            'date'      => \Carbon\Carbon::now()->formatLocalized('%d %B %Y')
        ];

        $mailer->send('emails.shop.confirmation', $data , function ($message) use ($user) {
            $message->to($user->email, $user->name)->subject('Confirmation de commande');
            $message->bcc('archive@publications-droit.ch', 'Archive publications-droit');
            $message->replyTo('bounce@publications-droit.ch', 'Réponse depuis publications-droit.ch');
        });
    }
}
