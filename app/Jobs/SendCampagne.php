<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendCampagne implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $campagne;
    public $html;
    public $emails;

    /**
     * @param \App\Droit\Newsletter\Entities\Newsletter_campagnes $campagne, string $html, array $emails
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
        $worker = \App::make('App\Droit\Newsletter\Worker\MailgunInterface');

        $worker->setSender($this->campagne->newsletter->from_email,$this->campagne->newsletter->from_name)
            ->setRecipients($this->emails)
            ->setHtml($this->html);

        $worker->sendCampagne($this->campagne);
    }
}
