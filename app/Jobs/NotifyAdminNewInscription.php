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

        \Mail::send('emails.notification', $infos, function ($m) {
            $m->from('droit.formation@unine.ch', 'Administration');
            $m->to('cindy.leschaud@gmail.com', 'Administration')->subject('Notification');
        });
    }
}
