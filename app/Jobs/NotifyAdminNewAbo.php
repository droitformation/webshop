<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use \Illuminate\Support\Collection;

class NotifyAdminNewAbo extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $abos;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Collection $abos)
    {
        $this->abos = $abos;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $infos = [
            'name'     => $this->abos->first()->user->name,
            'what'     => 'Demande d\'abonnement',
            'link'     => 'admin/abo',
            'abos'     => $this->abos
        ];

        \Mail::send('emails.notification', $infos, function ($m) {
            $m->from('info@publications-droit.ch', 'Administration Droit Formation');
            $m->to('info@publications-droit.ch', 'Administration')->subject('Notification');
        });
    }
}
