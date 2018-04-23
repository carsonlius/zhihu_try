<?php

namespace App\Listeners;

use App\Events\QuestionTopicDeleteEvent;
use App\Topic;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class QuestionTopicDeleteListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  QuestionTopicDeleteEvent  $event
     * @return void
     */
    public function handle(QuestionTopicDeleteEvent $event)
    {
        dump('删除操作的监听者模式');
       $topic_id  = $event->question_topic->topic_id;
       Topic::find($topic_id)->increment('questions_count', -1);
    }
}
