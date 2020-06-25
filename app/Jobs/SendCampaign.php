<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendCampaign implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $campagne;
    public $html;
    public $recipients;

    protected $mailjet;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($campagne ,$html, $recipients)
    {
        $this->campagne   = $campagne;
        $this->html       = $html;
        $this->recipients = $recipients;

        $this->mailjet  = \App::make('App\Droit\Newsletter\Worker\MailjetServiceInterface');

        setlocale(LC_ALL, 'fr_FR.UTF-8');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        echo '<pre>';
        print_r($this->mailjet);
        echo '</pre>';
        exit;
       // $this->mailjet->sendBulk($this->campagne,$this->html,$this->recipients,false);
    }
}
