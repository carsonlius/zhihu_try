<?php

namespace App;

use App\Collection\MessageCollection;
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
     * 将未阅读的私信标记为已阅读
     */
    public function  markAsRead()
    {
        if ($this->is_read === 'F') {
            $is_read = 'T';
            $read_at = now();
            $this->fill(compact('is_read', 'read_at'))->save();
        }
    }

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


    public function newCollection(array $models = [])
    {
        return new MessageCollection($models);
    }
}
