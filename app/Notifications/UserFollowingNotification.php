<?php

namespace App\Notifications;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class UserFollowingNotification extends Notification
{
    use Queueable;

    /*
     * 被关注用户用户通知
     * */
    private $queue_broadcast = 'queue_broadcast_user_following';

    /*
     * 被当前用户关注的用户的id列表
     *
     * */
    private $ids_following;

    /**
     * Create a new notification instance.
     *
     * @param array $ids_following 被关注用户的Id列表
     */
    public function __construct($ids_following)
    {
        $this->ids_following = $ids_following;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    /**
     * 写入数据库的notifications
     */
    public function toDatabase()
    {
        // 被关注的用户名字
        $list_name = array_map(function($id){
            return User::find($id)->name;
        }, $this->ids_following);

        $following_name = implode(',', $list_name);

        return compact('following_name');
    }


    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }

    /**
     * 发起broadcast
     * @param $notifiable
     * @return BroadcastMessage
     */
    public function toBroadcast($notifiable)
    {
        // 被关注的用户名字
        $list_name = array_map(function($id){
            return User::find($id)->name;
        }, $this->ids_following);

        $following_name = implode(',', $list_name);

        return (new BroadcastMessage(compact('following_name')))
            ->onConnection('redis')
            ->onQueue($this->queue_broadcast);
    }
}
