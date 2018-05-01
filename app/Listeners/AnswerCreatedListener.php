<?php

namespace App\Listeners;

use App\Events\AnswerCreatedEvent;
use App\Question;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AnswerCreatedListener
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
     * @param  AnswerCreatedEvent  $event
     * @return void
     */
    public function handle(AnswerCreatedEvent $event)
    {
        $event->answer->question()->increment('answers_count', 1);
    }
}
