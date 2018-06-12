<?php

namespace App\Jobs;

use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendRappelAboEmail implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $facture;
    protected $mailer;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($facture)
    {
        $this->facture  = $facture;

        setlocale(LC_ALL, 'fr_FR.UTF-8');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user   = $this->facture->abonnement->user_facturation;
        $rappel = $this->facture->rappels->sortBy('created_at')->last();

        $data = [
            'title'       => 'Abonnement sur publications-droit.ch',
            'concerne'    => 'Rappel',
            'abonnement'  => $this->facture->abonnement,
            'abo'         => $this->facture->abonnement->abo,
            'date'        => \Carbon\Carbon::now()->formatLocalized('%d %B %Y'),
        ];

        \Mail::send('emails.abo.rappel', $data , function ($message) use ($user,$rappel) {
            $message->to($user->email, $user->name)->subject('Rappel abonnement');
            $message->attach(public_path($rappel->doc_rappel), array('as' => 'Rappel.pdf', 'mime' => 'application/pdf'));
            $message->bcc('archive@publications-droit.ch', 'Archive publications-droit');
            $message->replyTo('info@publications-droit.ch', 'Réponse depuis publications-droit.ch');
        });

    }

}
