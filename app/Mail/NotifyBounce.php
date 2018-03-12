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
    public $remarque;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($bounce,$remarque)
    {
        $this->bounce = $bounce;
        $this->remarque = $remarque;
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
