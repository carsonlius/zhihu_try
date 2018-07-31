<?php

namespace App\Http\Repositories;

use App\Message;

class MessageRepository
{

    /**
     * 获取某个对话列表
     * @param integer $friend_id
     * @return array
     */
    public function show($friend_id)
    {
        $user_id = \Auth::id();
        return Message::where(compact('user_id', 'friend_id'))->with('fromUser')->orderBy('id', 'desc')->get();
    }

    /**
     * 登陆用户收到的私信
     * @return mixed
     */
    public function getMessageList()
    {   $user_id = $from_user_id = \Auth::id();
        return Message::where(compact('user_id'))
            ->with('friendUser')
            ->orderBy('id', 'desc')
            ->get()
            ->groupBy('friend_id');
    }

    /**
     * 没有阅读的私信的数量
     */
    public function unreadNum()
    {
        $user_id = \Auth::guard('api')->id();
        return Message::where(compact('user_id'))->unread()->count();
    }

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
        $from_user_id = user('api')->id;

        // 第一条私信属于发送者的
         $user_id = user('api')->id;
         $friend_id = $to_user_id;
         Message::create(compact('to_user_id', 'from_user_id', 'body', 'user_id', 'friend_id'));

         // 第二条私信属于接受者
        $user_id = $to_user_id;
        $friend_id = user('api')->id;
        return Message::create(compact('to_user_id', 'from_user_id', 'body', 'user_id', 'friend_id'));
    }
}