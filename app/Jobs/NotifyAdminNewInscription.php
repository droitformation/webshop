<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Illuminate\Contracts\Mail\Mailer;
use App\Droit\Inscription\Entities\Inscription;

class NotifyAdminNewInscription extends Job implements ShouldQueue
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
    public function handle()
    {
        $infos = [
            'name'     => $this->inscription->inscrit->name,
            'colloque' => $this->inscription->colloque->titre,
            'what'     => 'inscription',
            'link'     => 'admin/inscription/colloque/'.$this->inscription->colloque->id
        ];

        $email = $this->getEmail($this->inscription->colloque);

        \Mail::send('emails.notification', $infos, function ($m) use ($email) {
            $m->from('info@publications-droit.ch', 'Administration Droit Formation');
            $m->to($email, 'Administration')->subject('Notification');
        });
    }

    public function getEmail($colloque)
    {
        $environment = \App::environment();

        if($environment == 'production')
        {
            return $colloque->email ? $colloque->email : 'droit.formation@unine.ch';
        }

        return env('MAIL_TEST') ? env('MAIL_TEST') : 'cindy.leschaud@gmail.com';
    }
}
