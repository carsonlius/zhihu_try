<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['from_user_id', 'to_user_id', 'body', 'is_read', 'read_at', 'user_id', 'friend_id'];

    protected $dates = ['created_at', 'updated_at', 'read_at'];

    /*
     * 增加一个数据库中没有的字段
     * */
    protected $appends = ['created_at_human'];

    /**
     * 设置
     * @param $timestamp
     */
//    public function setReadAtAttribute($timestamp)
//    {
//        $this->attributes['read_at'] = Carbon::createFromTimestamp($timestamp);
//    }

    /**
     * 设置关于 create_at_human 字段的获取器
     * @return mixed
     */
    public function getCreatedAtHumanAttribute()
    {
        return Carbon::createFromTimeString($this->attributes['created_at'])->diffForHumans();
    }

    /**
     * 和当前私信相关联的用户
     */
    public function friendUser()
    {
        return $this->belongsTo(User::class, 'friend_id', 'id');
    }

    /**
     * 发起私信的用户（多对一）
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id', 'id');

    }

    /**
     * 接收私信的用户(多对一)
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function toUser()
    {
        return $this->belongsTo(User::class, 'to_user_id', 'id');
    }

    /**
     * only没有阅读的私信
     * @param $query
     * @return mixed
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', 'F');
    }
}
