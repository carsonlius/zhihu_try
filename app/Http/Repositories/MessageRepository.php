<?php

namespace App\Http\Repositories;

use App\Events\MessageCreateEvent;
use App\Message;
use App\Notifications\MessageNotification;
use App\User;

class MessageRepository
{
    /**
     * 将私信标记为已读
     */
    public function markAsRead()
    {
        $user_id = user('api')->id;
        $friend_id = request()->get('friend_id');
        $is_read = 'F';
        $where = compact('is_read', 'user_id', 'friend_id');
        $is_read = 'T';
        $read_at = now();
        return Message::where($where)
            ->update(compact('is_read', 'read_at'));
    }

    /**
     * 获取某个对话列表
     * @return array
     */
    public function inboxShow()
    {
        $user_id = user('api')->id;
        $friend_id = request()->get('friend_id');
        return Message::where(compact('user_id', 'friend_id'))
            ->with(['fromUser' => function ($query) {
                $query->select(['id', 'name', 'avatar']);
            }])->orderBy('id', 'desc')->get();
    }

    /**
     * 登陆用户收到的私信
     * @return mixed
     */
    public function getMessageList()
    {
        $user_id = \Auth::id();
        $list_messages = Message::where(compact('user_id'))
            ->with(['friendUser' => function ($query) {
                $query->select(['name', 'id', 'avatar']);
            }])
            ->orderBy('id', 'desc')
            ->get()
            ->groupBy('friend_id');

        // 为私信列表整理数据
        return $this->tidyMessageForList($list_messages);
    }

    /**
     *  为私信列表整理数据
     * @param array $list_messages
     * @return mixed
     */
    protected function tidyMessageForList($list_messages)
    {
        return  $list_messages->map(function ($item) {
            // 获取最新的一条信息
            $item_group = $item->first();

            // 当前数据中是否需要添加unread的类
            $item_group->unread_class = $this->unreadClass($item);
            return $item_group;
        });
    }

    /**
     * 当前数据中是否需要添加unread的类
     * @param array $item_messages
     * @return boolean
     */
    protected function unreadClass($item_messages)
    {
        $unread_class = false;
        $item_messages->each(function ($item) use (&$unread_class) {
            if ($item->user_id != $item->from_user_id && $item->is_read === 'F') {
                $unread_class = true;
                return false;
            }
        });

        return $unread_class;
    }

    /**
     * 没有阅读的私信的数量
     */
    public function unreadNum()
    {
        $user_id = \Auth::guard('api')->id();
        return Message::where(compact('user_id'))->where('from_user_id', '!=', $user_id)->unread()->count();
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
        $message_store = Message::create(compact('to_user_id', 'from_user_id', 'body', 'user_id', 'friend_id'));

        // 生成notification(这个和私信插件功能有些重复,后期拿掉，现在只是学习使用)
        $message_store->toUser->notify(new MessageNotification($message_store));

        // 返回新插入的私信
        $id = $message_store->id;
        return $message_created = Message::where(compact('id'))->with(['fromUser' => function ($query) {
            $query->select(['id', 'name', 'avatar']);
        }])->first();
    }
}