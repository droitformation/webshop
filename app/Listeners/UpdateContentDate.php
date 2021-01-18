<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateContentDate
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $satelites = [
            ['local' => 'https://rcanew.test/updateCache', 'production' => 'https://rcassurances.ch/updateCache'],
            ['local' => 'https://ddtnew.test/updateCache', 'production' => 'https://droitdutravail.ch/updateCache']
        ];

        $client = new \GuzzleHttp\Client(['verify' => false, 'http_errors' => false]); // ,'debug' => true

        foreach ($satelites as $satelite){
            $base_url_url = \App::environment() == 'local' ? $satelite['local'] : $satelite['production'];

            try {
                $response = $client->post($base_url_url);
                $data     = json_decode($response->getBody(), true);
            }
            catch (\GuzzleHttp\Exception\ConnectException $e) {
                \Log::info('Cache update failed');
                \Mail::to('droitformation.web@gmail.com')->send(new \App\Mail\WebmasterNotification('Le cache sur '.$base_url_url.' n\'as pas été mis à jour après des modifications'));
            }
        }

        setMaj(\Carbon\Carbon::today()->toDateString(),$event->what);
    }
}
