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

    /**
     * 给当前答案点过赞的用
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function votesUser()
    {
        return $this->belongsToMany(User::class, 'votes', 'answer_id', 'user_id')->withTimestamps();
    }

    /**
     * 评论的多态关联
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
