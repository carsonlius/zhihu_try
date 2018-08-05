<?php

namespace App\Http\Controllers;

use App\Repositories\NotificationsRepository;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\Notification;

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

    /**
     * 展示特定的notification
     * @param DatabaseNotification $notification
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function show(DatabaseNotification $notification)
    {
        $notification->markAsRead();
        $redirect = request()->get('redirect_url');
//        \Request::query('redirect_url')
        return redirect($redirect);
    }
}
