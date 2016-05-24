<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Illuminate\Contracts\Mail\Mailer;
use App\Droit\Inscription\Entities\Groupe;

class SendConfirmationGroupInscriptionEmail extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $group;
    protected $mailer;
    protected $email;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Groupe $group, $email = null)
    {
        setlocale(LC_ALL, 'fr_FR.UTF-8');

        // Allow us to pass another email to the job
        $this->email = $email;
        $this->group = $group;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Mailer $mailer)
    {
        $generator = \App::make('App\Droit\Generate\Pdf\PdfGeneratorInterface');
        
        $this->group->load('colloque','inscriptions');
        $annexes = $this->group->colloque->annexe;

        // Generate annexes if any
        if(empty($this->group->documents) && !empty($annexes))
        {
            foreach($annexes as $annexe)
            {
                foreach($this->group->inscriptions as $inscription)
                {
                    // Make the bon and the other docs if the price is not 0
                    if($annexe == 'bon' || ($inscription->price_cents > 0 && ($annexe == 'facture' || $annexe == 'bv')))
                    {
                        $generator->make($annexe, $inscription);
                    }
                }
            }
        }

        $user         = $this->group->user;
        $attachements = $this->group->documents;

        $data = [
            'title'        => 'Votre inscription sur publications-droit.ch',
            'logo'         => 'facdroit.png',
            'concerne'     => 'Inscription',
            'annexes'      => $this->group->colloque->annexe,
            'participants' => $this->group->participant_list,
            'colloque'     => $this->group->colloque,
            'user'         => $this->group->user,
            'date'         => \Carbon\Carbon::now()->formatLocalized('%d %B %Y'),
        ];

        $mailer->send('emails.colloque.confirmation', $data , function ($message) use ($user,$attachements) {

            // Overwrite the email to send to?
            $email = ($this->email ? $this->email : $user->email);

            $message->to($email, $user->name)->subject('Confirmation d\'inscription');

            // Attach all documents
            if(!empty($attachements))
            {
                foreach($attachements as $attachement)
                {
                    $message->attach($attachement['file'], ['as' => $attachement['name'], 'mime' => 'application/pdf']);
                }
            }
        });

        // Update the send date and add true if send via admin
        foreach($this->group->inscriptions as $inscription)
        {
            $inscription->send_at = date('Y-m-d');
            $inscription->admin   = ($this->email ? 1 : null);
            $inscription->save();
        }
    }

}
