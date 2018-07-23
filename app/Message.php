<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['from_user_id', 'to_user_id', 'body', 'is_read', 'read_at'];

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
