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
        $this->client = new \GuzzleHttp\Client(['curl' => [CURLOPT_SSL_VERIFYPEER => false]]);
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
                //'product' => $item->toArray(),
                'user'    => [
                    "name"  => $event->order->order_adresse->name,
                    "email" => $event->order->order_adresse->email,
                ]
            ];

            \Log::info('Envoi code to: '.json_encode($data));

            $response  = $this->client->post( $item->notify_url, ['query' => $data]);

            $data      = json_decode($response->getBody(), true);

            \Log::info('Envoi code response: '.json_encode($data));
        });

    }
}
