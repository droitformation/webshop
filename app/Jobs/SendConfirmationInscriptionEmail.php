<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Mail\Mailer;
use App\Droit\Inscription\Entities\Inscription;

class SendConfirmationInscriptionEmail extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $inscription;
    protected $mailer;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Inscription $inscription)
    {
        $this->inscription  = $inscription;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Mailer $mailer)
    {
        $generator = new \App\Droit\Generate\Pdf\PdfGenerator();

        $annexes   = $this->inscription->colloque->annexe;
        // Generate annexes
        if(!empty($annexes))
        {
            foreach($annexes as $annexe)
            {
                $doc = $annexe.'Event';
                $generator->$doc($this->inscription);
            }
        }

        setlocale(LC_ALL, 'fr_FR.UTF-8');

        $date   = \Carbon\Carbon::now()->formatLocalized('%d %B %Y');
        $title  = 'Votre inscription sur publications-droit.ch';
        $logo   = 'facdroit.png';

        $user = $this->inscription->user;

        $data = [
            'title'       => $title,
            'logo'        => $logo,
            'inscription' => $this->inscription
        ];

/*        $facture = public_path().'/files/shop/factures/facture_'.$order->order_no.'.pdf';
        $name    = 'facture_'.$order->order_no.'.pdf';*/

        $mailer->send('emails.colloque.confirmation', $data , function ($message) use ($user) {
            $message->to($user->email, $user->name)->subject('Confirmation d\'inscription');

/*            if($facture)
            {
                $message->attach($facture, array('as' => $name, 'mime' => 'application/pdf'));
            }*/

        });
    }
}
