<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendRegisterConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $date;
    public $title;
    public $annexes;
    public $colloque;
    public $user;
    public $inscription;
    public $attachements;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($title,$annexes,$colloque,$user,$inscription,$attachements)
    {
        $this->title = $title;
        $this->annexes = $annexes;
        $this->colloque = $colloque;
        $this->user = $user;
        $this->inscription = $inscription;
        $this->attachements = $attachements;
        $this->date = \Carbon\Carbon::now()->formatLocalized('%d %B %Y');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mail = $this->from(config('mail.from.address'))->subject('Confirmation d\'inscription')->view('emails.colloque.confirmation');

        if(!empty($attachements) && config('inscription.link') == false) {
            foreach($attachements as $attachement) {
                $mail->attach($attachement['file'], ['as' => isset($attachement['pdfname']) ? $attachement['pdfname'] : '', 'mime' => 'application/pdf']);
            }
        }

        return $mail;
    }
}
