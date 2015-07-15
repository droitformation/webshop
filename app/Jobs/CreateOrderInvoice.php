<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Droit\Shop\Order\Entities\Order;

class CreateOrderInvoice extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $order;
    protected $generator;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $order  = \App::make('App\Droit\Shop\Order\Repo\OrderInterface');
        $user   = \App::make('App\Droit\User\Repo\UserInterface');

        $generator = new \App\Droit\Generate\Pdf\PdfGenerator($order,$user);

        // Generate invoice
        $generator->factureOrder($this->order->id);
    }
}
