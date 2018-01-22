<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SubscriberEmailUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $email_old;
    public $email_new;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($email_old,$email_new)
    {
        $this->email_old = $email_old;
        $this->email_new = $email_new;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
