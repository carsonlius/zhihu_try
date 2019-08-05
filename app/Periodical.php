<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Periodical extends Model
{
    protected $fillable = [
        'month', // 期刊创建的月份
        'img', // 封面
        'title',
        'des', //描述
        'type', // 类型'music','movie','text','book'
        'sort_weight', // 排序
        'fav_nums', // 点赞次数
        'periodical_index', // 第几期
        'published', // 发布状态  1 待发布 2 已发布 3 撤回
        'data', // 配置
        'created_at', 'updated_at'
    ];

    /**
     * 属于的用户
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\WechatUser', 'id', 'user_id');
    }

    /**
     * 获取多个单元
     * @param array $where
     * @param null $filed
     * @param null $order
     * @return mixed
     */
    public static function getOneItem(array $where, $filed = null, $order = null)
    {
        return static::where($where)
            ->when($filed, function($query) use($filed){
                return $query->select($filed);
            })->when($order, function($query) use ($order){
                return $query->orderBy($order[0], $order[1]);
            })->first();
    }

    /**
     * 获取多个单元
     * @param array $where
     * @param null $filed
     * @param null $order
     * @return mixed
     */
    public static function getItems(array $where, $filed = null, $order = null)
    {
        return static::where($where)
            ->when($filed, function($query) use($filed){
                return $query->select($filed);
            })->when($order, function($query) use ($order){
                return $query->orderBy($order[0], $order[1]);
            })->get();
    }
}
