<?php

namespace App;

use App\Events\QuestionCreatedEvent;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = ['title', 'user_id', 'body', 'flowers_count', 'comments_count', 'close_comment', 'is_hidden'];

    public function isHidden()
    {
        return $this->is_hidden === 'T';
    }

    public function isClose()
    {
        return $this->close_comment = 'T';
    }

    protected $dispatchesEvents = [
        'created' => QuestionCreatedEvent::class
    ];

    public function topic()
    {
        return $this->belongsToMany(Topic::class)->withTimestamps();
    }
}
