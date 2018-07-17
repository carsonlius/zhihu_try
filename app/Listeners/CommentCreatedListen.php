<?php

namespace App\Listeners;

use App\Answer;
use App\Events\CommentCreatedEvent;
use App\Question;
use App\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CommentCreatedListen
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
     * @param  CommentCreatedEvent  $event
     * @return void
     */
    public function handle(CommentCreatedEvent $event)
    {
        $commentable_type =$event->comment->commentable_type;
        $commentable_id = $event->comment->commentable_id;
        $user_id = $event->comment->user_id;

        User::find($user_id)->increment('comments_count');

        // 如果类型是问题
        if (strtolower($commentable_type) == 'app\question') {
            Question::find($commentable_id)->increment('comments_count');
        } else {
            Answer::find($commentable_id)->increment('comments_count');
        }
    }
}
