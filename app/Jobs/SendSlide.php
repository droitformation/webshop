<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendSlides;

class SendSlide implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public $email;
    public $colloque;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email ,$colloque)
    {
        $this->email = $email;
        $this->colloque = $colloque;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
         $texte = 'Lorem ad quîs j\'libéro pharétra vivamus mollis ultricités ut, vulputaté ac pulvinar èst commodo aenanm pharétra cubilia, lacus aenean pharetra des ïd quisquées mauris varius sit. Mie dictumst nûllam çurcus molestié imperdiet vestibulum suspendisse tempor habitant sit pélléntésque sit çunc, primiés ?';
         $url   = secure_url('pubdroit/documents/'.$this->colloque->id);

         Mail::to($this->email)->bcc('archive@publications-droit.ch')->send(new SendSlides($this->colloque,$texte,$url));
    }
}
