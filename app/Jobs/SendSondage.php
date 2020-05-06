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

        $subject = $this->sondage->marketing ? $this->sondage->title : 'Sondage pour le colloque '.$this->sondage->colloque->titre;
        $from = $this->sondage->marketing && !empty($this->sondage->organisateur) ? $this->sondage->organisateur : 'Publications-droit';

        \Log::info($subject);
        \Mail::send('emails.sondage', $donnes, function ($m) use($donnes,$subject,$from) {
            $m->from('info@publications-droit.ch',$from)->to($donnes['email'], $subject)->subject($subject);
        });
    }
}
