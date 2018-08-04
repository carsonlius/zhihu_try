<?php

namespace App\Http\Controllers;

use App\Repositories\NotificationsRepository;

class NotificationsController extends Controller
{
    protected $repository_notify;

    /**
     * NotificationsController constructor.
     * @param $repository_notify
     */
    public function __construct(NotificationsRepository $repository_notify)
    {
        $this->repository_notify = $repository_notify;
    }

    /**
     * 通知列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $user = \Auth::user();
        $user->unreadNotifications->markAsRead();
        return view('notifications.index', compact('user'));
    }

    /**
     * 没有阅读的私信的数量
     * @return integer
     */
    public function unreadNum()
    {
        try {
            $number_unread = $this->repository_notify->unreadNum();
            $status = 0;
            return response()->json(compact('status', 'number_unread'));
        } catch (\Exception $e) {
            $status = 1478;
            $msg = $e->getMessage();
            return response()->json(compact('status', 'msg'));
        }
    }
}
