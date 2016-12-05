<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class MakeRappelOrder extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    
    protected $order;
    protected $orders;
    protected $rappel;
    protected $generator;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($orders)
    {

        $this->generator = \App::make('App\Droit\Generate\Pdf\PdfGeneratorInterface');
        $this->rappel = \App::make('App\Droit\Shop\Rappel\Repo\RappelInterface');
        $this->order  = \App::make('App\Droit\Shop\Order\Repo\OrderInterface');

        $this->orders = $orders;

        setlocale(LC_ALL, 'fr_FR.UTF-8');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if(empty($this->orders)){ return true; }

        $orders = $this->order->getMultiple($this->orders);

        if(!$orders->isEmpty()) {

            foreach($orders as $order)
            {
               if($order->rappels->isEmpty())
               {
                   $rappel = $this->rappel->create(['order_id' => $order->id]);

                   if($rappel)
                   {
                       $order->load('rappels');
                       
                       $this->generator->setMsg(['warning' => config('generate.rappel.normal')]);
                       $this->generator->factureOrder($order, $rappel);
                   }
               }
            }

        }
    }

}
