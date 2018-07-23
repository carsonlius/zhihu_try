<?php

namespace App\Http\Controllers;

use App\Http\Repositories\MessageRepository;
use App\Message;
use Illuminate\Http\Request;

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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
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
     *
     * @param  \App\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function show(Message $message)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function edit(Message $message)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Message $message)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function destroy(Message $message)
    {
        //
    }
}
