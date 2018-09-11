<?php

namespace App\Listeners;

use App\Events\OrderUpdated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyOrderUpdated
{
    protected $client;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        $this->client = new \GuzzleHttp\Client(['curl' => [CURLOPT_SSL_VERIFYPEER => false], 'http_errors' => false]);
    }

    /**
     * Handle the event.
     *
     * @param  OrderUpdated  $event
     * @return void
     */
    public function handle(OrderUpdated $event)
    {
        $helper = new \App\Droit\Helper\Helper();

        // get products
        $products = $helper->prepareNotifyEvent($event->order);

        // test if any has a request to do
        $products->each(function ($item, $url) use ($event) {
            // make request with data
            \Log::info('qty: '.$item.' url: '.$url);

            $data = [
                'order_id' => $event->order->id,
                'qty' => $item,
                'user'    => [
                    "name"  => $event->order->order_adresse->name,
                    "email" => $event->order->order_adresse->email,
                ]
            ];

            $response = $this->client->post( $url, ['query' => $data]);

            if($response->getStatusCode() != 200) {
                \Mail::to('droitformation.web@gmail.com')->send(
                    new \App\Mail\WebmasterNotification('Problème avec le code d\'accès à envoyer à ' . $event->order->order_adresse->email . ' order: ' . $event->order->id)
                );
            }

        });
    }

}
