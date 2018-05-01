<?php

namespace App;

use App\Events\AnswerCreatedEvent;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $fillable = ['user_id', 'question_id', 'body', 'votes_count', 'comments_count', 'is_hidden', 'close_comment'];

    protected $dispatchesEvents = [
        'created' => AnswerCreatedEvent::class
    ];

    /**
     * relationshi
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * relationship
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
