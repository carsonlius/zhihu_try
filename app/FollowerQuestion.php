<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FollowerQuestion extends Model
{
    protected $fillable = ['user_id', 'question_id'];
    protected $table = 'follower_question';
}
