<?php

namespace App\Repositories;

use App\Answer;

class AnswerRepository
{
    /**
     * 将表单提交的数据存储到数据库中
     * @param array $request
     */
    public function store($request)
    {
        $params = $request->toArray();
        $params['user_id'] = \Auth::id();
        Answer::create($params);
    }

}