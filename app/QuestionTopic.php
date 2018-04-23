<?php

namespace App;

use App\Events\QuestionTopicDeleteEvent;
use Illuminate\Database\Eloquent\Model;

class QuestionTopic extends Model
{
    protected $table = 'question_topic';
    protected $dispatchesEvents = [
        'deleted' => QuestionTopicDeleteEvent::class
    ];
}
