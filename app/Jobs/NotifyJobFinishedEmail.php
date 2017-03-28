<?php

namespace App\Jobs;

use App\Jobs\Job;
use Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyJobFinishedEmail extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $text;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($text)
    {
        $this->text = $text;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::send('emails.notify', ['text' => $this->text], function ($m) {

            $email = env('MAIL_TEST') ? env('MAIL_TEST') : 'info@publications-droit.ch';

            $m->from('info@publications-droit.ch', 'Administration Droit Formation');
            $m->to($email, 'Administration')->subject('Notification');
        });
    }
}
