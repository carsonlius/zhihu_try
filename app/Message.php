<?php

namespace App;

use App\Collection\MessageCollection;
use App\Events\MessageCreateEvent;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['from_user_id', 'to_user_id', 'body', 'is_read', 'read_at', 'user_id', 'friend_id'];

    protected $dates = ['created_at', 'updated_at', 'read_at'];

    /**
     * 是否有未读信息
     */
    public function addUnreadClass($from_user_id)
    {
        // 如果最新一条的信息是登陆用户发送的,则返回false （现在的逻辑是如果最新的一条有被阅读 则所有的私信肯定也是被阅读过的）
        if ($from_user_id == \Auth::id()) {
            return false;
        }

        return $this->is_read === 'F';
    }
    /*
     * 增加一个数据库中没有的字段
     * */
    protected $appends = ['created_at_human'];

    protected $dispatchesEvents = [
        'created' => MessageCreateEvent::class
    ];


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
