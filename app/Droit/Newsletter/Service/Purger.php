<?php namespace App\Droit\Newsletter\Service;

class Purger
{
    protected $client;
    protected $url;
    protected $api_key = '';

    public function __construct(\GuzzleHttp\Client $client)
    {
        $this->client  = $client;
        $this->url     = (\App::environment() == 'testing' ? 'https://shop.test' : 'https://api.debounce.io/v1/');
        $this->api_key = config('services.debounce.api');
    }

    public function verify($email)
    {
        $response = $this->client->get($this->url, ['query' => ['api' => $this->api_key, 'email' =>  $email]]);

        if($response->getStatusCode() == 200){
            return json_decode($response->getBody(), true);
        }

        if($response->getStatusCode() == 402){
            \Mail::to('droitformation.web@gmail.com')->send(new \App\Mail\NotifyWebmaster('Plus de crédits sur debounce.io'));
        }

        if($response->getStatusCode() == 403){
            throw new \App\Exceptions\DebounceException('Maximum concurrent calls reached');
        }
    }

    public function status($result)
    {
        // The api call didn't work log the result
        if(isset($result['success']) && $result['success'] == 0){
            \Log::info(['Problem with debounce :', json_encode($result)]);
            return null;
        }

        if(isset($result['balance']) && $result['balance'] < 10){
            \Mail::to('droitformation.web@gmail.com')->send(new \App\Mail\NotifyWebmaster('Bientôt plus de crédits sur debounce.io'));
        }

        if(isset($result['success']) && $result['success'] == 1){
            return $result['debounce']['result'];
        }
    }
}