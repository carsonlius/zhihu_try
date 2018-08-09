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
        user('api')->followed()->toggle($user_created);

        // 查看当前登陆用户是否已经关注了创建问题的用户
        $followed = user('api')->followed->contains('id', $user_created);
        $status = 0;
        return compact('followed', 'status');
    }
    /**
     * 上传文件的头像
     * @throws \Exception
     */
    public function avatarUpload()
    {
        if (!request()->hasFile('img_avatar')) {
            throw new \Exception('缺少上传的图像文件');
        }

        // 存储图片
        $file_name = '/avatars/' . md5(time() . user()->id) . '.' . request()->img_avatar->extension();
        // 存储到七牛云
        $response_upload = \Storage::disk('qiniu')->put($file_name, fopen(request()->img_avatar, 'r'));
        if ($response_upload === false) {
            throw new \Exception('上传到七牛云失败');
        }

        // 更新用户的avatar
        $avatar = 'http://' . env('QINIU_DOMAIN') . $file_name;
        $id = user()->id;
        User::where(compact('id'))->update(compact('avatar'));
        return $avatar;
    }
}
