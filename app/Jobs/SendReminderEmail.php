<?php

namespace App\Jobs;

use App\Droit\Reminder\Entities\Reminder;

use App\Jobs\Job;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendReminderEmail extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $reminder;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Reminder $reminder)
    {
        $this->reminder = $reminder;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Mailer $mailer)
    {
        $item = null;

        if(isset($this->reminder->model) && !empty($this->reminder->model))
        {
            $model = new $this->reminder->model;

            if($model instanceof \Illuminate\Database\Eloquent\Model)
            {
                $model_id = $this->reminder->model_id;
                $item     = $model->find($model_id);
            }
        }

        $mailer->send('emails.reminder', ['reminder' => $this->reminder, 'item' => $item], function ($m)
        {
            $m->from('droit.formation@unine.ch', 'Droit Formation');
            $m->to('droit.formation@unine.ch', 'Droit Formation')->subject('Rappel');
        });

        $this->reminder->delete();
    }
}
