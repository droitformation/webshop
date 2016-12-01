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

    protected $factures;
    protected $facture;
    protected $worker;
    protected $mailer;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($factures)
    {
        $this->factures = $factures;
        $this->facture  = \App::make('App\Droit\Abo\Repo\AboFactureInterface');
        $this->worker   = \App::make('App\Droit\Abo\Worker\AboWorker');

        setlocale(LC_ALL, 'fr_FR.UTF-8');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if(empty($this->factures)){ return true; }

        $factures = $this->facture->getMultiple($this->factures);

        if(!$factures->isEmpty())
        {
            foreach($factures as $facture)
            {
                $this->send($facture);
            }
        }

        return true;
    }

    protected function send($facture)
    {
        $user   = $facture->abonnement->user;
        $rappel = $facture->rappels->sortBy('created_at')->last();

        $data = [
            'title'       => 'Abonnement sur publications-droit.ch',
            'concerne'    => 'Rappel',
            'abonnement'  => $facture->abonnement,
            'abo'         => $facture->abonnement->abo,
            'date'        => \Carbon\Carbon::now()->formatLocalized('%d %B %Y'),
        ];

        \Mail::send('emails.abo.rappel', $data , function ($message) use ($user,$rappel) {
            $message->to($user->email, $user->name)->subject('Rappel abonnement');
            $message->attach(public_path($rappel->abo_rappel), array('as' => 'Rappel.pdf', 'mime' => 'application/pdf'));
        });
    }
}
