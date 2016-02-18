<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Droit\Reminder\Repo\ReminderInterface;
use App\Jobs\SendReminderEmail;

class SendReminder extends Command
{
     use DispatchesJobs;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminder emails';

    protected $reminder;
    protected $job;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ReminderInterface $reminder, SendReminderEmail $job)
    {
        parent::__construct();

        $this->reminder = $reminder;
        $this->job      = $job;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // reminders to send today
        $reminders = $this->reminder->toSend();

        if(!$reminders->isEmpty())
        {
            foreach($reminders as $reminder)
            {
                $this->dispatch( (new SendReminderEmail($reminder)) );
            }
        }

    }
}
