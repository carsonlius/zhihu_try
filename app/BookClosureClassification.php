<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BookClosureClassification extends Model
{
    protected $fillable = [
        'name'
    ];

    protected $table = 'book_closure_classification';

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
