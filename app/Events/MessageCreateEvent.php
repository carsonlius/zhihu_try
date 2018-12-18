<?php

namespace App\Events;

use App\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MessageCreateEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $from_user;
    public $message;

    public $broadcastQueue = 'private-message-to-created';

    public function broadcastWhen()
    {
        return $this->message->user_id == $this->message->to_user_id;
    }

    /**
     * Create a new event instance.
     *
     * @param Message $message
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
        $this->from_user = $this->message->fromUser;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        $private_counter_channel = new PrivateChannel('message-to-counter.' . $this->message->user_id);
        $private_created_channel = new PrivateChannel('message-to-created.' . $this->message->user_id);
        return [
            $private_counter_channel,
            $private_created_channel
        ];
    }
}
