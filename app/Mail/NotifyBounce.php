<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyBounce extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The order instance.
     *
     * @var Order
     */
    public $bounce;
    public $headers;
    public $body;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($bounce,$headers,$body)
    {
        $this->bounce = $bounce;
        $this->headers = $headers;
        $this->body = $body;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('info@publications-droit.ch')->subject('Email non valide')->view('emails.bounce');
    }
}
