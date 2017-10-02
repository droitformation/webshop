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

    protected $inscriptions;
    protected $inscription;
    protected $worker;
    protected $mailer;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($inscriptions)
    {
        $this->inscriptions = $inscriptions;
        $this->inscription  = \App::make('App\Droit\Inscription\Repo\InscriptionInterface');
        $this->worker       = \App::make('App\Droit\Inscription\Worker\RappelWorkerInterface');

        setlocale(LC_ALL, 'fr_FR.UTF-8');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if(empty($this->inscriptions)){ return true; }

        $inscriptions = $this->inscription->getMultiple($this->inscriptions);

        if(!$inscriptions->isEmpty()) {
            foreach($inscriptions as $inscription) {
                $this->send($inscription);
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
            'inscription' => $inscription,
            'user'        => $user,
            'date'        => \Carbon\Carbon::now()->formatLocalized('%d %B %Y'),
        ];

        \Mail::send('emails.colloque.rappel', $data , function ($message) use ($user,$rappel,$inscription) {

            $message->to($user->email, $user->name)->subject('Rappel');
            $message->bcc('archive@publications-droit.ch', 'Archive publications-droit');
            $message->replyTo('bounce@publications-droit.ch', 'Réponse depuis publications-droit.ch');

            $message->attach(public_path($rappel->doc_rappel), array('as' => 'Rappel.pdf', 'mime' => 'application/pdf'));

            if($inscription->doc_bv){
                $message->attach(public_path($inscription->doc_bv), array('as' => 'Bv.pdf', 'mime' => 'application/pdf'));
            }
        });
    }
}
