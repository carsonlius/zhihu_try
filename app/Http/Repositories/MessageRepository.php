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
        // 检查条件
        $this->validateParamsForStore();


        // 第一条私信属于发送者的
        $this->createSelfMessage();

        // 第二条私信属于接受者
        return $this->createFriendMessage();
    }

    /**
     * 新建一条属于好友的私信
     *
     */
    private function createFriendMessage()
    {
        //参数
        $params = $this->genParamsForFriendMessage();

        // 生成私信
        $message_store = Message::create($params);

        // 返回
        return $this->getNewMessage($message_store->id);
    }

    /**
     * @param $id
     * @return mixed
     */
    private function getNewMessage($id)
    {
        return Message::where(compact('id'))->with(['fromUser' => function ($query) {
            $query->select(['id', 'name', 'avatar']);
        }])->first();
    }

    private function genParamsForFriendMessage()
    {
        $user_id = $to_user_id = request()->post('to_user_id');
        $body = request()->post('body');
        $from_user_id = $friend_id = user('api')->id;
        return compact('friend_id', 'to_user_id', 'body', 'user_id', 'from_user_id');
    }

    /**
     * @throws \Exception
     */
    private function validateParamsForStore()
    {
        if (!request()->post('body', '', 'trim')) {
            throw new \Exception('请输入私信内容');
        }
    }

    /**
     * 新建一条属于登陆用户的私信
     * @throws \Exception
     */
    private function createSelfMessage()
    {
        // 参数
        $params = $this->genParamsForSelf();
        // 第一条私信属于发送者的
        Message::create($params);
    }

    /**
     * 参数
     * @return array
     */
    private function genParamsForSelf()
    {
        $friend_id = $to_user_id = request()->post('to_user_id');
        $body = request()->post('body');
        $user_id = $from_user_id = user('api')->id;
        return compact('friend_id', 'to_user_id', 'body', 'user_id', 'from_user_id');
    }
}