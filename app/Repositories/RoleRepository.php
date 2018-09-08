<?php

namespace App\Repositories;

use App\User;
use Ultraware\Roles\Models\Permission;
use Ultraware\Roles\Models\Role;

class RoleRepository
{

    /**
     * 更新角色下辖的权限
     * @throws \Exception
     */
    public function updatePermission()
    {
        // 检测参数
        $this->verifyParams();

        // 权限collections
        $list_permission = $this->genParamsForPermission();

        // 更新
        $this->updateRolePermission($list_permission);
    }

    /**
     * 特定用户绑定的角色
     * @throws \Exception
     * @return array
     */
    public function which()
    {
        // 校验参数
        $this->verifyParamsForWhich();

        // 条件
        $id = trim(request()->get('user_id', ''));

        // 绑定的角色
        return User::find($id)->roles;
    }

    /**
     * 为绑定的角色校验参数
     * @throws \Exception
     */
    protected function verifyParamsForWhich()
    {
        $user_id = request()->get('user_id');
        $user_id = trim($user_id);
        if (!$user_id) {
            throw new \Exception('请输入查询的用户ID');
        }
    }

    /**
     * 检测参数
     * @throws \Exception
     */
    protected function verifyParams()
    {
        $role_id = request()->post('role_id');
        if (!$role_id) {
            throw new \Exception('缺少要更新的角色ID');
        }

        $list_permission_name = request()->post('list_permission_name');
        if (!is_array($list_permission_name)) {
            throw new \Exception('要分配的角色必须是数组');
        }
    }

    /**
     * 更新角色下辖的权限
     * @param collection $list_permission
     */
    protected function updateRolePermission($list_permission)
    {
        $role_id = request()->post('role_id');
        Role::find($role_id)->syncPermissions($list_permission);
    }

    /**
     * 为更新权限配置生成参数
     * @return array
     */
    protected function genParamsForPermission()
    {
        $list_permission_name = request()->post('list_permission_name');

        // 转成对应的permission对象
        return Permission::whereIn('name', $list_permission_name)->get();
    }


    /**
     * 获取某个角色对应得权限
     */
    public function getRolePermission()
    {
         $role_id = request()->get('role_id');
         $list_permission = Role::find($role_id)->permissions->toArray();
         return json_encode(array_column($list_permission, 'name'), JSON_UNESCAPED_UNICODE);
    }

    /**
     * API 编辑角色
     * @throws \Exception
     */
    public function update()
    {
        // 校验参数
        $this->verifyParamsForEdit();

        // 编辑角色
        $this->EditRole();
    }

    /**
     * 编辑角色
     */
    protected function EditRole()
    {
        $params = request()->only(['level', 'name', 'slug', 'description']);
        $id = request()->post('role_id');
        return Role::where(compact('id'))->update($params);
    }

    /**
     * 为编辑角色校验参数
     * @throws \Exception
     */
    protected function verifyParamsForEdit()
    {
        // 校验选择的字段的是否存在
        $this->verifyExistForEdit();

        // 校验slug的唯一性
        $this->verifyUniqueSlugForEdit();
    }

    /**
     * 为编辑角色校验slug的唯一性
     * @throws \Exception
     */
    protected function verifyUniqueSlugForEdit()
    {
        $slug = request()->post('slug');
        $role_id = request()->post('role_id');
        $id = [
            '!=', $role_id
        ];
        $exist = Role::where(compact('slug', 'id'))->first();
        if ($exist) {
            throw new \Exception('您所填写的slug已经被占用，请重新选择');
        }
    }


    /**
     * 为编辑校色校验参数是否存在
     * @throws \Exception
     */
    protected function verifyExistForEdit()
    {
        // name slug level
        $name = request()->post('name');
        if (!$name) {
            throw new \Exception('请填写角色名称');
        }

        $slug = request()->post('slug');
        if (!$slug) {
            throw new \Exception('请填写唯一标识');
        }

        $level = request()->post('level');
        if(!$level) {
            throw new \Exception('请选择角色的级别');
        }

        $role_id = request()->post('role_id');
        if(!$role_id) {
            throw new \Exception('请传递要编辑的角色的ID');
        }
    }

    /**
     * 角色列表
     */
    public function list()
    {
        return Role::all();
    }

    /**
     * 存取角色
     * @throws \Exception
     */
    public function store()
    {
        // 校验参数
        $this->verifyParamsForStore();

        // 存取角色
       return $this->storeRole();
    }

    /**
     * 存取角色
     * @return boolean
     */
    protected function storeRole()
    {
       $params = request()->only(['level', 'name', 'slug', 'description']);
        return Role::create($params);
    }

    /**
     * 为存角色校验参数
     * @throws \Exception
     */
    protected function verifyParamsForStore()
    {
        // 校验选择的字段的是否存在
        $this->verifyExistForSore();

        // 校验slug的唯一性
        $this->verifyUniqueSlugForStore();
    }

    /**
     * 为存取角色校验slug的唯一性
     * @throws \Exception
     */
    protected function verifyUniqueSlugForStore()
    {
        $slug = request()->post('slug');
        $exist = Role::where(compact('slug'))->first();
        if ($exist) {
            throw new \Exception('您所填写的slug已经被占用，请重新选择');
        }
    }

    /**
     * 为存取校色校验参数是否存在
     * @throws \Exception
     */
    protected function verifyExistForSore()
    {
        // name slug level
        $name = request()->post('name');
        if (!$name) {
            throw new \Exception('请填写角色名称');
        }

        $slug = request()->post('slug');
        if (!$slug) {
            throw new \Exception('请填写唯一标识');
        }

        $level = request()->post('level');
        if(!$level) {
            throw new \Exception('请选择角色的级别');
        }
    }
}