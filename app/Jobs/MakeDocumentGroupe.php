<?php

namespace App\Jobs;

use App\Jobs\Job;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Droit\Inscription\Entities\Groupe;

class MakeDocumentGroupe extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $groupe;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Groupe $groupe)
    {
        setlocale(LC_ALL, 'fr_FR.UTF-8');

        $this->groupe = $groupe;
        $this->groupe->load('colloque','inscriptions','user');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $generator = \App::make('App\Droit\Generate\Pdf\PdfGeneratorInterface');

        $annexes   = $this->groupe->colloque->annexe;

        // Make all bons
        if(!$this->groupe->inscriptions->isEmpty() && in_array('bon',$annexes))
        {
            foreach($this->groupe->inscriptions as $inscription)
            {
                $generator->make('bon', $inscription);
            }
        }

        if($this->groupe->price > 0)
        {
            $generator->make('facture', $this->groupe);
            $generator->make('bv', $this->groupe);
        }
    }
}
