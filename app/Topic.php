<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    protected $fillable = ['name', 'brief', 'questions_count', 'essences_count', 'followers_count', 'image'];

    public function questions()
    {
        return $this->belongsToMany(Question::class)->withTimestamps();
    }
}
