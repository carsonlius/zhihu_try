<?php

namespace App\Repositories;


class NotificationsRepository
{
    public function unreadNum()
    {
        return count(user('api')->unreadNotifications);
    }

}