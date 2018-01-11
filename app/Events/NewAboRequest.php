<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use \Illuminate\Support\Collection;

class NewAboRequest extends Event
{
    use SerializesModels;

    public $abos;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Collection $abos)
    {
        $this->abos = $abos;
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