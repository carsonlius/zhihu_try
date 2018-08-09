<?php

namespace App\Events;

use App\QuestionTopic;
use App\Topic;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class QuestionTopicDeleteEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $question_topic;

    /**
     * Create a new event instance.
     *
     * @param QuestionTopic $question_topic
     */
    public function __construct(QuestionTopic $question_topic)
    {
        $this->question_topic = $question_topic;
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
