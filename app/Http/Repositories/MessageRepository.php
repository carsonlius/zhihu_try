<?php

namespace App\Http\Repositories;

use App\Message;

class MessageRepository
{
    /**
     * 存储私信
     * @throws \Exception
     * @return boolean
     */
    public function store()
    {
        $to_user_id = request()->post('to_user_id');
        $body = request()->post('body');
        if (!$body) {
            throw new \Exception('请填写私信内容');
        }

        $from_user_id = \Auth::guard('api')->user()->id;

        return Message::create(compact('to_user_id', 'from_user_id', 'body'));
    }
}