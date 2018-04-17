<?php

namespace App\Jobs;

use Exception;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendRappelEmail implements ShouldQueue
{
    public $tries = 1;

    use InteractsWithQueue, Queueable, SerializesModels;

    public $inscription;
    protected $worker;
    protected $mailer;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($inscription)
    {
        $this->inscription = $inscription;
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
        try {

            $inscription = $this->inscription;

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

            if($inscription->group_id && isset($inscription->groupe)) {
                $data['participants'] = $inscription->groupe->participant_list;
            }

            \Mail::send('emails.colloque.rappel', $data , function ($message) use ($user,$rappel,$inscription) {

                $message->to($user->email, $user->name)->subject('Rappel');
                $message->bcc('archive@publications-droit.ch', 'Archive publications-droit');
                $message->replyTo('info@publications-droit.ch', 'Réponse depuis publications-droit.ch');

                $message->attach(public_path($rappel->doc_rappel), array('as' => 'Rappel.pdf', 'mime' => 'application/pdf'));

                if($inscription->doc_bv){
                    $message->attach(public_path($inscription->doc_bv), array('as' => 'Bv.pdf', 'mime' => 'application/pdf'));
                }
            });
        }
        catch(Exception $e) {
            // bird is clearly not the word
            $this->failed($e);
        }
    }

    public function failed(\Exception $e)
    {
        $infos = [
            'name'  => 'Problème avec le rappels',
            'what'  => 'Problème avec l\'envoi du rappel',
            'rappel' => $this->inscription->inscription_no,
            'link'  => url('admin/colloques')
        ];

        \Mail::send('emails.notification', $infos, function ($m) {
            $m->from('info@publications-droit.ch', 'Administration Droit Formation');
            $m->to('droit.formation@unine.ch', 'Administration')->subject('Problème avec l\'envoi du rappel');
        });
    }
}
