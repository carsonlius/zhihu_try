<?php

namespace App;

use App\Events\TopicDeleteEvent;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    protected $fillable = ['name', 'brief', 'questions_count', 'essences_count', 'followers_count', 'image'];

    protected $dispatchesEvents = [
        'deleted' => TopicDeleteEvent::class
    ];

    public function questions()
    {
        return $this->belongsToMany(Question::class)->withTimestamps();
    }
}
