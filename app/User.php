<?php

namespace App;

use App\Collection\SettingCollection;
use App\Events\UserCreateEvent;
use App\Notifications\UserFollowedNotification;
use App\Notifications\UserFollowingNotification;
use App\Notifications\UserNotFollowingNotification;
use Fico7489\Laravel\Pivot\Traits\PivotEventTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Ultraware\Roles\Traits\HasRoleAndPermission;
use Ultraware\Roles\Contracts\HasRoleAndPermission as HasRoleAndPermissionContract;

class User extends Authenticatable implements HasRoleAndPermissionContract
{
    use Notifiable, PivotEventTrait,HasRoleAndPermission;

    protected $casts = [
        'settings' => 'array'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'avatar', 'confirmation_token', 'is_active', 'questions_count',
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
    protected $dispatchesEvents = [
        'created' => UserCreateEvent::class
    ];


    /**
     * 用户设置
     * @return SettingCollection
     */
    public function settings()
    {
        return new SettingCollection($this);
    }

    /**
     * 当前用户发起的评论(一对多)
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * 发起的私信（一对多）
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function hasFromMessage()
    {
        return $this->hasMany(Message::class, 'from_user_id', 'id');
    }

    /**
     * 接受的私信(一对多)
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function hasToMessage()
    {
        return $this->hasMany(Message::class, 'to_user_id', 'id');
    }

    /**
     * 当前用户点赞过的答案（多对多关系）
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function votesAnswer()
    {
       return $this->belongsToMany(Answer::class, 'votes', 'user_id', 'answer_id')->withTimestamps();
    }

    public static function boot()
    {
        parent::boot();

        static::pivotAttaching(function ($model, $relationName, $pivotIds, $pivotIdsAttributes) {
        });

        static::pivotAttached(function ($model, $relationName, $pivotIds, $pivotIdsAttributes) {
            // 登陆用户关注其他用户的事件(attached事件)
            if ($relationName === 'followed') {
                // 登陆用户的关注(其他的)用户数增加
                $count_attached = count($pivotIds);
                $model->increment('followers_count', $count_attached);

                // 发起关注的notifications
                $model->notify(new UserFollowingNotification($pivotIds));

                // 下面的用户的被关注数目加1
                array_walk($pivotIds, function ($user_id) {
                    self::find($user_id)->increment('following_count');
                    self::find($user_id)->notify(new UserFollowedNotification());
                });
            }


            // 答案点赞事件
            if ($relationName == 'votesAnswer') {
                array_walk($pivotIds, function($answer_id){
                    Answer::find($answer_id)->increment('votes_count');
                });
            }

        });

        static::pivotDetaching(function ($model, $relationName, $pivotIds) {

        });

        static::pivotDetached(function ($model, $relationName, $pivotIds) {
            // 登陆用户不在关注其他的用户事件(detached 事件)
            if ($relationName === 'followed') {
                // 下面的用户不在关注别人
                $count_detached = count($pivotIds);
                $model->decrement('followers_count', $count_detached);

                // 取消关注的notification
//                $model->notify(new UserNotFollowingNotification($pivotIds));

                // 下面的用户不再受关注
                array_walk($pivotIds, function($user_id){
                    self::find($user_id)->decrement('following_count');
                });
            }

            // 答案取消点赞事件
            if ($relationName == 'votesAnswer') {
                array_walk($pivotIds, function($answer_id){
                    Answer::find($answer_id)->decrement('votes_count');
                });
            }
        });

        static::pivotUpdating(function ($model, $relationName, $pivotIds, $pivotIdsAttributes) {
//            dump($model);
//            dump($relationName);
//            dump($pivotIds);
//            dump($pivotIdsAttributes);
        });

        static::pivotUpdated(function ($model, $relationName, $pivotIds, $pivotIdsAttributes) {
//            dump($model);
//            dump($relationName);
//            dump($pivotIds);
//            dump($pivotIdsAttributes);
        });

        static::updating(function ($model) {
//            dump($model);
        });
    }

    /**
     * 关注当前用户的
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function followers()
    {
        return $this->belongsToMany(self::class, 'followers', 'followed_id','follower_id')->withTimestamps()
            ->withTimestamps();
    }

    /**
     * 被当前用户关注的用户
     */
    public function followed()
    {
        return $this->belongsToMany(self::class, 'followers', 'follower_id', 'followed_id')->withTimestamps();
    }


    /**
     * 当前用户是否关注了当前问题
     * @param $question_id
     * @return boolean
     */
    public function followThisQuestion($question_id)
    {
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
