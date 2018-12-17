<?php

namespace App\Listeners;

use App\Mail\UserVerifyMail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserCreateListener
{
    private $queue_enable_user = 'queue_enable_active_user';

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
     * @param  object $event
     * @return void
     */
    public function handle($event)
    {
        // 赋值游客角色
        $event->user->attachRole(7);

        // 激活邮件
        $mail = (new UserVerifyMail($event->user))
            ->onQueue($this->queue_enable_user)
            ->onConnection('redis');

        \Mail::to($event->user->email)
            ->queue($mail);
    }
}
