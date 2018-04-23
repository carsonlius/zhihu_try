<?php

namespace App\Listeners;

use App\Events\TopicDeleteEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class TopicDeleteListener
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
     * @param  TopicDeleteEvent  $event
     * @return void
     */
    public function handle(TopicDeleteEvent $event)
    {
//        $event->topic->increment('questions_count', -1);
    }
}
