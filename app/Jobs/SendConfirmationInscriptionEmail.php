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
        setlocale(LC_ALL, 'fr_FR.UTF-8');
        $this->inscription = $inscription;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Mailer $mailer)
    {
        $generator = \App::make('App\Droit\Generate\Pdf\PdfGeneratorInterface');
        
        $this->inscription->load('colloque');
        $annexes = $this->inscription->colloque->annexe;

        // Generate annexes if any
        if(empty($this->inscription->documents) && !empty($annexes))
        {
            foreach ($annexes as $annexe)
            {
                // Make the bon and the other docs if the price is not 0
                if($annexe == 'bon' || ($this->inscription->price_cents > 0 && ($annexe == 'facture' || $annexe == 'bv')))
                {
                    $generator->make($annexe, $this->inscription);
                }
            }
        }

        $user         = $this->inscription->user;
        $attachements = $this->inscription->documents;

        $data = [
            'title'       => 'Votre inscription sur publications-droit.ch',
            'logo'        => 'facdroit.png',
            'concerne'    => 'Inscription',
            'annexes'     => $this->inscription->colloque->annexe,
            'colloque'    => $this->inscription->colloque,
            'user'        => $this->inscription->user,
            'date'        => \Carbon\Carbon::now()->formatLocalized('%d %B %Y'),
        ];

        $mailer->send('emails.colloque.confirmation', $data , function ($message) use ($user,$attachements) {

            $message->to($user->email, $user->name)->subject('Confirmation d\'inscription');

            // Attach all documents
            if(!empty($attachements))
            {
                foreach($attachements as $attachement)
                {
                    $message->attach($attachement['file'], array('as' => $attachement['name'], 'mime' => 'application/pdf'));
                }
            }
        });

        // Update the send date and add true if send via admin
        $this->inscription->send_at = date('Y-m-d');
        $this->inscription->admin   = ($this->email ? 1 : null);
        $this->inscription->save();

    }

}
