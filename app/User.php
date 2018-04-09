<?php

namespace App;

use App\Events\UserCreateEvent;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','avatar', 'confirmation_token', 'is_active', 'questions_count',
        'answers_count', 'comments_count', 'favorites_count', 'likes_count', 'followers_count', 'following_count',
        'settings'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected  $dispatchesEvents = [
        'created' => UserCreateEvent::class
    ];
}
