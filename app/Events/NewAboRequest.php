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
    public $user;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Collection $abos, $user)
    {
        $this->abos = $abos;
        $this->user = $user;
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