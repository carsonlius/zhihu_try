<?php

namespace App;

use App\Events\UserCreateEvent;
use Illuminate\Database\Eloquent\Model;
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
        'settings', 'api_token'
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

    /**
     * 当前用户是否关注了当前问题
     * @param $question_id
     * @return boolean
     */
    public function followThisQuestion($question_id)
    {
        if (!\Auth::check()) {
            return false;
        }
        return \Auth::user()->questionFollow->contains('id', $question_id);
    }

    /**
     * 判断登录用户是不是该Model里面的user_id字段是一个
     * @param Model $model
     * @return boolean
     */
    public function owns(Model $model)
    {
        return \Auth::check() ? (\Auth::id() == $model->user_id) : false;
    }

    /**
     * 关注的问题(多对多)
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function questionFollow()
    {
        return $this->belongsToMany(Question::class, 'follower_question', 'user_id', 'question_id')->withTimestamps();
    }
}
