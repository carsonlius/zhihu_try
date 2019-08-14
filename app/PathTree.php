<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PathTree extends Model
{
    protected $fillable = [
        'name', 'code', 'path'
    ];

    protected $table = 'path_tree';

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

    /**
     * 获取多个单元
     * @param array $where
     * @param array $field
     * @return mixed
     */
    public static function getItems(array $where, array $field = [])
    {
        return static::where($where)
            ->when($field, function($query) use($field){
                return $query->select($field);
            })->get();

    }
}
