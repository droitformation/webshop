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
    public $error;
    public $remarque;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($bounce,$error,$remarque)
    {
        $this->bounce = $bounce;
        $this->error = $error;
        $this->remarque = $remarque;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('info@publications-droit.ch')->sender(config('mail.from.address'))->subject('Email non valide')->view('emails.bounce');
    }
}
