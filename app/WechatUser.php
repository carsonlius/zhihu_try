<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;
use Illuminate\Auth\Authenticatable;
use Illuminate\Foundation\Auth\Access\Authorizable;

class WechatUser extends Model
{
    use HasApiTokens,Authenticatable, Authorizable;
    protected $fillable = [
        'name', // 昵称
        'wechat_id', // 微信openId
        'fav_nums', // 点赞的数量
        'comment_nums', // 评论的数量
        'country', // 国家
        'province', // 省份
        'city', // 城市
        'avatar_url', // 头像地址
    ];

    /**
     * 获取一个单元
     * @param array $where
     * @param array $field
     * @return mixed
     */
    public static function getOneItem(array $where, array $field = [])
    {
        return static::where($where)
            ->when($field, function($query) use($field){
                return $query->select($field);
            })->first();

    }
}
