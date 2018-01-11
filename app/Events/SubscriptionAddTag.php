<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SubscriptionAddTag
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $adresse;
    public $newsletter_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($adresse,$newsletter_id)
    {
        $this->adresse = $adresse;
        $this->newsletter_id = $newsletter_id;
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
