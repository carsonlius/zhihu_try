<?php

namespace App\Repositories;

use Ultraware\Roles\Models\Role;

class RoleRepository
{

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