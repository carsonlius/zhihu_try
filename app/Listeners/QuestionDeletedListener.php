<?php

namespace App\Listeners;

use App\Events\QuestionDeletedEvent;
use App\Topic;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class QuestionDeletedListener
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
     * @param  QuestionDeletedEvent  $event
     * @return void
     */
    public function handle(QuestionDeletedEvent $event)
    {
        // 1. 删除了问题 那么话题下面的问题数量少了1个,同时question_topic 应该被删除
        // 2. 因为第一步 所以不介入去出发多对多的sync事件

//        $question_id = $event->question->id;
//        Topic::where(compact('question_id'))->increment('question_count', -1);
    }
}
