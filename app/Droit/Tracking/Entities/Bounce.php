<?php namespace App\Droit\Tracking\Entities;

class Bounce
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function headers()
    {
       $headers = $this->data['headers'];
       
       return [
            'Date'   => $headers['Date'],
            'De'     => $headers['From'],
            'A'      => $headers['To'],
            'Suject' => $headers['Subject'],
       ];
    }

    public function failed()
    {
        $headers = $this->data['headers'];

        return isset($headers['X-Failed-Recipients']) ? $headers['X-Failed-Recipients'] : false;
    }

    public function body()
    {
        $plain = isset($this->data['plain']) && !empty($this->data['plain']) ? $this->data['plain'] : '';
        $html  = isset($this->data['html']) && !empty($this->data['html']) ? $this->data['html'] : '';

        return !empty($html) ? $html : $plain;
    }
}