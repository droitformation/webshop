<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendSlides extends Mailable
{
    use Queueable, SerializesModels;

    public $colloque;
    public $texte;
    public $url;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($colloque,$texte,$url)
    {
        $this->colloque = $colloque;
        $this->texte = $texte;
        $this->url = $url;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = $this->colloque->titre.' - Colloque du '.$this->colloque->event_date.' - VOS DOCUMENTS A TELECHARGER';

        $program = $this->colloque->programme_attachement;

        $mail = $this->subject($subject);

        if($program){
            $mail->attach($program['file'], ['as' => 'Programme.pdf', 'mime' => 'application/pdf']);
        }

        return $mail->view('emails.slide');
    }
}
