<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendSondage implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public $sondage;
    public $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($sondage ,$data)
    {
        $this->sondage = $sondage;
        $this->data    = $data;
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
            'email'      => $this->data['email'],
            'isTest'     => isset($this->data['isTest']) ? $this->data['isTest'] : null,
        ]));

        $donnes = [
            'sondage' => $this->sondage,
            'email'   => $this->data['email'],
            'url'     => $url
        ];

        $subject = $this->sondage->marketing ? $this->sondage->title : 'le colloque '.$this->sondage->colloque->titre;

        \Mail::send('emails.sondage', $donnes, function ($m) use($donnes,$subject) {
            $m->to($donnes['email'], 'Sondage')->subject('Sondage pour '.$subject);
        });
    }
}
