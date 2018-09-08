<?php

namespace App\Repositories;

use App\User;

class UserRepository
{
    /**
     * 更新用户所属的角色
     * @throws \Exception
     */
    public function updateRole()
    {
        // 校验参数
        $this->verifyParamsForUpdateRole();

        // 更新
        $this->updateRoleDo();
    }

    /**
     * 更新用户所属的角色
     */
    protected function updateRoleDo()
    {
        $user_id = request()->input('user_id');
        $list_role_ids = request()->input('list_role_ids');
        User::find($user_id)->syncRoles($list_role_ids);
    }

    /**
     * 为更新用户所属的角色校验参数
     * @throws \Exception
     */
    protected function verifyParamsForUpdateRole()
    {
        if (!request()->has('list_role_ids')) {
            throw new \Exception('请输入用户所属的角色ID');
        }
        $user_id = request()->input('user_id');
        $user_id = trim($user_id);
        if (!$user_id) {
            throw new \Exception('请输入要更新的用户ID');
        }
    }

    /**
     * 给用户赋角色
     * @throws \Exception
     * @return array
     */
    public function roleAssign()
    {
        // 校验参数
        $this->verifyParamForAssign();

        // 参数列表
        return $this->getParamsList();
    }

    /**
     * 参数列表
     * @return array
     */
    protected function getParamsList()
    {
        return request()->only(['user_id', 'user_name']);
    }

    /**
     * 校验参数
     * @throws \Exception
     */
    protected function verifyParamForAssign()
    {
        $user_id = request()->get('user_id');
        $user_name = request()->get('user_name');
        if (!$user_id) {
            throw new \Exception('请输入要检查的用户ID');
        }
        if (!$user_name) {
            throw new \Exception('请输入要检查用户的名称');
        }
    }

    /**
     * 用户角色列表
     */
    public function list()
    {
        // 条件
        $where = $this->genConditionForList();
        return User::where($where)->with('roles')->get();
    }

    /**
     * 用户角色列表的条件
     * @return array
     */
    protected function genConditionForList()
    {
        $id = request()->post('user_id');
        if (!$id) {
            return [];
        }
        return compact('id');
    }

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
