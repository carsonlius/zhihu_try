<?php

namespace App\Repositories;

use App\Question;

class FollowerQuestionRepository
{
    public function store(Question $question)
    {
        // 创建关系
        $user_id = \Auth::id();
        $question->followers()->toggle($user_id);
    }
}
