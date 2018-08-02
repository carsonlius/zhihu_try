<?php

namespace App\Http\Controllers;

use App\Http\Repositories\MessageRepository;
use App\Message;
use App\User;
use Illuminate\Http\Request;
use League\Flysystem\Exception;

class MessageController extends Controller
{
    private $repository_message;

    public function __construct(MessageRepository $message_repository)
    {
        $this->repository_message = $message_repository;
    }

    /**
     * 没有阅读的私信的数量
     */
    public function unreadNum()
    {
        try {
            $number_unread = $this->repository_message->unreadNum();
            $status = 0;
            return response()->json(compact('status', 'number_unread'));
        } catch (\Exception $e) {
            $status = 1478;
            $msg = $e->getMessage();
            return response()->json(compact('status', 'msg'));
        }
    }

    /**
     * 将未读私信标记为已读私信
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAsRead()
    {
        try {
            $result_mark = $this->repository_message->markAsRead();
            if ($result_mark === false) {
                throw new \Exception('标记已读失败');
            }
            $status = 0;
            $msg = '已经标记为已读';
            return response()->json(compact('msg', 'status'));
        } catch (\Exception $e) {
            $status = 1478;
            $msg = $e->getMessage();
            return response()->json(compact('msg', 'status'));
        }
    }

    /**
     * 当前用户收到的私信列表
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $messages = $this->repository_message->getMessageList();
        return view('message.inbox')->with(compact('messages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        try {
            $result_store = $this->repository_message->store();
            $status = $result_store ? 0 : 7777;
            $msg = $result_store ? '私信已发送' : '网络故障，请再次发送私信';
            return response()->json(compact('status', 'msg', 'result_store'));
        } catch (\Exception $e) {
            $status = 7777;
            $msg = $e->getMessage();
            return response()->json(compact('status', 'msg'));
        }
    }

    /**
     * Display the specified resource.
     * @param integer $friend_id
     * @return \Illuminate\Http\Response
     */

    public function show($friend_id)
    {
        $friend_name = User::find($friend_id)->name;
        return view('message.show')->with(compact('friend_id', 'friend_name'));
    }

    /**
     * 获取登陆用户和特定用户之间所有私信信息
     * @return array
     */
    public function inboxShow()
    {
        try {
            $data = $this->repository_message->inboxShow();
            // 将查询到数据标记为阅读
            $data->markAsRead();

            $msg = '查询成功';
            $status = 0;
            return response()->json(compact('data', 'msg', 'status'));
        } catch (\Exception $e) {

            $status = 1478;
            $msg = $e->getMessage();
            return response()->json(compact('status', 'msg'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Message $message
     * @return \Illuminate\Http\Response
     */
    public function edit(Message $message)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Message $message
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Message $message)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Message $message
     * @return \Illuminate\Http\Response
     */
    public function destroy(Message $message)
    {
        //
    }
}
