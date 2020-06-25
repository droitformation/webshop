<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CampaignSent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $newsletter_id;
    public $campaign_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($newsletter_id,$campaign_id)
    {
        $this->campaign_id   = $campaign_id;
        $this->newsletter_id = $newsletter_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
