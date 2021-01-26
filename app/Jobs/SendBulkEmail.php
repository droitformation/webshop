<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendBulkEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $campagne;
    public $html;
    public $emails;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($campagne,$html,$emails)
    {
        $this->campagne = $campagne;
        $this->html     = $html;
        $this->emails   = $emails;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        /************ MAILJET ************/
        $mailjet = \App::make('App\Droit\Newsletter\Worker\MailjetServiceInterface');

        $mailjet->sendBulk($this->campagne,$this->html,$this->emails, null);
    }
}
