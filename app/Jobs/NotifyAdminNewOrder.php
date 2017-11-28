<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Illuminate\Contracts\Mail\Mailer;
use App\Droit\Shop\Order\Entities\Order;

class NotifyAdminNewOrder extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $order;
    protected $mailer;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order  = $order;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $infos = [
            'name'  => $this->order->user->name,
            'what'  => 'commande',
            'order' => $this->order->order_no,
            'link'  => url('admin/orders')
        ];

        \Mail::send('emails.notification', $infos, function ($m) {
            $m->from('info@publications-droit.ch', 'Administration Droit Formation');
            $m->bcc('archive@publications-droit.ch', 'Archive publications-droit');
            $m->to('droit.formation@unine.ch', 'Administration')->subject('Nouvelle commande depuis le site publications-droit.ch');
        });
    }
}
