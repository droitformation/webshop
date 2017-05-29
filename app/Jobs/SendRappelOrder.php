<?php

namespace App\Jobs;

use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendRappelOrder implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $order;
    protected $orders;
    protected $mailer;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($orders)
    {
        $this->order  = \App::make('App\Droit\Shop\Order\Repo\OrderInterface');
        $this->orders = $orders;

        setlocale(LC_ALL, 'fr_FR.UTF-8');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if(empty($this->orders)){ return true; }

        $orders = $this->order->getMultiple($this->orders);

        if(!$orders->isEmpty())
        {
            foreach($orders as $order)
            {
                $this->send($order);
            }
        }

        return true;
    }

    protected function send($inscription)
    {
        $user   = $inscription->inscrit;
        $rappel = $inscription->list_rappel->sortBy('created_at')->last();

        $data = [
            'title'       => 'Votre inscription sur publications-droit.ch',
            'concerne'    => 'Rappel',
            'annexes'     => $inscription->colloque->annexe,
            'colloque'    => $inscription->colloque,
            'user'        => $user,
            'date'        => \Carbon\Carbon::now()->formatLocalized('%d %B %Y'),
        ];

        \Mail::send('emails.order.rappel', $data , function ($message) use ($user,$rappel) {

            $message->to($user->email, $user->name)->subject('Rappel');
            $message->attach(public_path($rappel->doc_rappel), array('as' => 'Rappel.pdf', 'mime' => 'application/pdf'));
            $message->bcc('archive@publications-droit.ch', 'Archive publications-droit');
            $message->replyTo('bounce@publications-droit.ch', 'Réponse depuis publications-droit.ch');
        });
    }
}
