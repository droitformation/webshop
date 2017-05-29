<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Mail\Mailer;
use \Illuminate\Support\Collection;

class SendAboConfirmation extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $abos;
    protected $mailer;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Collection $abos)
    {
        $this->abos = $abos;

        setlocale(LC_ALL, 'fr_FR.UTF-8');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Mailer $mailer)
    {
        $user = $this->abos->first()->user;

        $total = $this->abos->map(function($item, $key){
            return $item->abo->price;
        })->sum();

        $data = [
            'title'     => 'Votre demande d\'abonnement sur publications-droit.ch',
            'concerne'  => 'Votre demande d\'abonnement',
            'logo'      => 'facdroit.png',
            'abos'      => $this->abos,
            'total'     => number_format((float) ($total/100), 2, '.', ''),
            'user'      => $user,
            'date'      => \Carbon\Carbon::now()->formatLocalized('%d %B %Y')
        ];

        $mailer->send('emails.shop.demande', $data , function ($message) use ($user) {
            $message->to($user->email, $user->name)->subject('Confirmation');
            $message->bcc('archive@publications-droit.ch', 'Archive publications-droit');
            $message->replyTo('bounce@publications-droit.ch', 'Réponse depuis publications-droit.ch');
        });

    }
}
