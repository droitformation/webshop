<?php

namespace App\Listeners;

use App\Events\OrderUpdated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyOrderUpdated
{
    protected $clients;

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
        // get products
        // test if any has a request to do
        $products = $event->order->products->filter(function ($product, $key) {
            return $product->notify_url;
        })->each(function ($item, $key) use ($event) {
            // make request with data
            $data = [
                'order_id' => $event->order->id,
                'user'    => [
                    "name"  => $event->order->order_adresse->name,
                    "email" => $event->order->order_adresse->email,
                ]
            ];

            $response = $this->client->post( $item->notify_url, ['query' => $data]);

            if($response->getStatusCode() != 200) {
                \Mail::to('cindy.leschaud@gmail.com')->send(
                    new \App\Mail\WebmasterNotification('Problème avec le code d\'accès à envoyer à ' . $event->order->order_adresse->email . ' order: ' . $event->order->id)
                );
            }
        });

    }
}
