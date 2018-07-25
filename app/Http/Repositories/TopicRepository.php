<?php

namespace App\Http\Repositories;

use App\Topic;

class TopicRepository
{
    /**
     * 查询特定的topic是否存在
     * @return mixed
     */
    public function getList()
    {
        $search = request()->input('search');
        return Topic::select(['id', 'name'])->where('name', 'like', '%' . $search . '%')->get();
    }
}