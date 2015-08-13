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
        $this->inscription = $inscription;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Mailer $mailer)
    {
        $this->inscription->load('colloque');
        $annexes = $this->inscription->colloque->annexe;

        // Generate annexes if any
        if(empty($this->inscription->documents) && !empty($annexes))
        {
            $this->generate($annexes);
        }

        setlocale(LC_ALL, 'fr_FR.UTF-8');

        $date   = \Carbon\Carbon::now()->formatLocalized('%d %B %Y');
        $title  = 'Votre inscription sur publications-droit.ch';
        $logo   = 'facdroit.png';

        $user    = $this->inscription->user;
        $annexes = $this->inscription->documents;

        $data = [
            'title'       => $title,
            'logo'        => $logo,
            'concerne'    => 'Inscription',
            'annexes'     => $this->inscription->colloque->annexe,
            'inscription' => $this->inscription,
            'date'        => $date,
        ];

        $mailer->send('emails.colloque.confirmation', $data , function ($message) use ($user,$annexes) {

            $message->to($user->email, $user->name)->subject('Confirmation d\'inscription');

            if(!empty($annexes))
            {
                foreach($annexes as $annexe)
                {
                    $message->attach($annexe['file'], array('as' => $annexe['name'], 'mime' => 'application/pdf'));
                }
            }
        });

        $this->inscription->send_at = date('Y-m-d');
        $this->inscription->save();
    }

    public function generate($annexes){

        $generator = new \App\Droit\Generate\Pdf\PdfGenerator();

        foreach($annexes as $annexe)
        {
            $doc = $annexe.'Event';
            $generator->$doc($this->inscription);
        }
    }

}
