<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendSondage implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $email;
    protected $sondage_id;
    protected $isTest;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email, $sondage, $isTest)
    {
        $this->email   = $email;
        $this->sondage = $sondage;
        $this->isTest  = $isTest;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $url = base64_encode(json_encode([
            'sondage_id' => $this->sondage->id,
            'email'      => $this->email,
            'isTest'     => 1,
        ]));

        $data = [
            'sondage' => $this->sondage,
            'email'   => $this->email,
            'url'     => $url
        ];

        \Mail::send('emails.sondage', $data, function ($m) {
            $m->from('droit.formation@unine.ch', 'www.publications-droit.ch');
            $m->to($this->email, 'Sondage')->subject('Sondage pour le colloque '. $this->sondage->colloque->titre);
        });
    }
}
