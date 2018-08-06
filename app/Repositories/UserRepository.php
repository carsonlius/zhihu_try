<?php

namespace App\Repositories;


use App\User;
use Qiniu\Http\Request;

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

    /**
     * 上传文件的头像
     * @throws \Exception
     */
    public function avatarUpload()
    {
        try {
            if (!request()->hasFile('img_avatar')) {
                throw new \Exception('缺少上传的图像文件');
            }

            // 存储图片
            $file_name = md5(time() . user()->id) . '.' . request()->img_avatar->extension();
            request()->img_avatar->move(public_path('/avatars'), $file_name);
        } catch (\FileException $e) {
            throw new \Exception($e->getMessage());
        }

        // 更新用户的avatar
        $avatar = asset('/avatars/' . $file_name);
        $id = user()->id;
        User::where(compact('id'))->update(compact('avatar'));
    }
}
