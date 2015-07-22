<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use App\Droit\Inscription\Entities\Inscription;

class InscriptionWasRegistered extends Event
{
    use SerializesModels;

    public $inscription;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function   __construct(Inscription $inscription)
    {
        $this->inscription = $inscription;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
