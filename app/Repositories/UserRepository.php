<?php

namespace App\Repositories;


use App\User;

class UserRepository
{
    /**
     * 根据ID查找用户
     * @param $id
     * @return mixed
     */
    public function byId($id)
    {
        return User::find($id);
    }


    public function follow($user_created)
    {
        \Auth::guard('api')->user()->followed()->toggle($user_created);

        // 查看当前登陆用户是否已经关注了创建问题的用户
        $followed = \Auth::guard('api')->user()->followed->contains('id', $user_created);
        $status = 0;
        return compact('followed', 'status');
    }
}
