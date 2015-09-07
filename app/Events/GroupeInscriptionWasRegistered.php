<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

use App\Droit\Inscription\Entities\Groupe;

class GroupeInscriptionWasRegistered extends Event
{
    use SerializesModels;

    public $groupe;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function  __construct(Groupe $groupe)
    {
        $this->groupe = $groupe;
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
