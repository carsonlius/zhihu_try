<?php

namespace App\Notifications;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class UserNotFollowingNotification extends Notification
{
    use Queueable;

    /*
     * 不再被关注的用户ID列表
     * */
    private $list_ids;

    /**
     * Create a new notification instance.
     *
     * @param array $list_ids 不再被关注的用户ID列表
     */
    public function __construct($list_ids)
    {
        $this->list_ids = $list_ids;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase()
    {
        $list_user =  array_map(function($id){
            $name = User::find($id)->name;
            return compact('name', 'id');
        }, $this->list_ids);
        return compact('list_user');
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
}
