<?php

namespace App\Jobs;

use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendRappelEmail implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $colloque_id;
    protected $inscription;
    protected $worker;
    protected $mailer;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($colloque_id)
    {
        $this->colloque_id = $colloque_id;
        $this->inscription = \App::make('App\Droit\Inscription\Repo\InscriptionInterface');
        $this->worker      = \App::make('App\Droit\Inscription\Worker\RappelWorkerInterface');

        setlocale(LC_ALL, 'fr_FR.UTF-8');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $inscriptions = $this->inscription->getRappels($this->colloque_id);

        if(!$inscriptions->isEmpty())
        {
            foreach($inscriptions as $inscription)
            {
                $this->send($inscription);
            }
        }
    }

    protected function send($inscription)
    {
        $user   = $inscription->inscrit;
        $rappel = $inscription->rappels->sortBy('created_at')->last();

        $data = [
            'title'       => 'Votre inscription sur publications-droit.ch',
            'concerne'    => 'Rappel',
            'annexes'     => $inscription->colloque->annexe,
            'colloque'    => $inscription->colloque,
            'user'        => $user,
            'date'        => \Carbon\Carbon::now()->formatLocalized('%d %B %Y'),
        ];

        \Mail::send('emails.colloque.rappel', $data , function ($message) use ($user,$rappel) {

            $message->to($user->email, $user->name)->subject('Rappel');
            $message->attach(public_path($rappel->doc_rappel), array('as' => 'Rappel.pdf', 'mime' => 'application/pdf'));
            
        });
    }
}
