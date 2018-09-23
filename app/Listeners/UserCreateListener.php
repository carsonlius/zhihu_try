<?php

namespace App\Listeners;

use App\Mail\UserVerifyMail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserCreateListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        // 赋值游客角色
        $event->user->attachRole(7);

        // 激活邮件
        \Mail::to($event->user->email)
            ->queue(new UserVerifyMail($event->user));
    }
}
