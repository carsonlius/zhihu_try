<?php

namespace App\Repositories;

use App\Question;

class FollowerQuestionRepository
{
    public function store(Question $question)
    {
        // 创建关系
        $user_id = \Auth::id();
        $question->followers()->attach($user_id);

        // 增加问题的关注数
        $question->increment('flowers_count', 1);
    }
}
