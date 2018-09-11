<?php

namespace App\Repositories;

use Ultraware\Roles\Models\Permission;
use Ultraware\Roles\Models\Role;

class PermissionRepository
{
    // 递归存取各个节点的信息
    protected $list_recursive;


    /**
     * 获取指定的权限(在权限树中的位置)
     * @throws \Exception
     */
    public function show()
    {
        // 检查参数
        $this->verifyParamsForShow();

        // 获取权限树
        return $this->getTreeShow();
    }

    /**
     * 获取权限树
     */
    protected function getTreeShow()
    {
        // 全量的权限
        $permission_list = $this->permissions();

        // 父级权限
        $permission_parent = $this->getParentPermissionForShow();

        // 生成权限树
        return $this->genPermissionTreeForShow($permission_list, $permission_parent);
    }

    /**
     * 父级权限
     * @return array
     */
    protected function getParentPermissionForShow()
    {
        $id = request()->post('parent_id');
        return Permission::where(compact('id'))->first();
    }

    /**
     * 生成权限树
     * @param array $permission_list
     * @param array $permission_parent
     * @return array
     */
    protected function genPermissionTreeForShow($permission_list, $permission_parent)
    {
        $tree = [];
        foreach ($permission_list as $item) {
            $permission_list[$item['id']]['title'] = $permission_list[$item['id']]['name'];
            $permission_list[$item['id']]['expanded'] = true;
            $permission_list[$item['id']]['selected'] = !$permission_parent ? false : $item['id'] === $permission_parent['id'];

            if(isset($permission_list[$item['parent_id']])){
                $permission_list[$item['parent_id']]['children'][] = &$permission_list[$item['id']];
            }else{
                $tree[] = &$permission_list[$item['id']];
            }
        }

        // 父级name
        $parent_name = !!$permission_parent ? $permission_parent['name'] : '请选择父级权限';
        return compact('tree', 'parent_name');
    }

    /**
     * 全量的权限
     */
    protected function permissions()
    {
        $list_permission = Permission::all()->toArray();
        return array_column($list_permission, null, 'id');
    }

    /**
     * 为指定权限的权限树检查参数
     * @throws \Exception
     */
    protected function verifyParamsForShow()
    {
        $parent_id = request()->get('parent_id');
        if ($parent_id === '') {
            throw new \Exception('请输入父级权限ID');
        }
    }

    /**
     * 已经有一部分已经被选中得节点树
     */
    public function tree()
    {
        // 选中节点
        $list_permission = $this->getSelectedPermission();

        // 全部节点
        $list_collection = $this->getAllPermission();

        // 生成节点树状结构
        return $this->generateSelectedTree($list_collection, $list_permission);
    }

    /**
     * 生成选中得无限树
     * @param $items
     * @param $list_permission
     * @return array
     */
    public function generateSelectedTree($items, $list_permission)
    {
        $tree = array();
        foreach ($items as $item) {
            $items[$item['id']]['title'] = $items[$item['id']]['name'];
            $items[$item['id']]['expanded'] = true;
            if (in_array($item['name'], $list_permission)) {
                $items[$item['id']]['checked'] = true;
            }

            if (isset($items[$item['parent_id']])) {
                $items[$item['parent_id']]['children'][] = &$items[$item['id']];
            } else {

                $tree[] = &$items[$item['id']];
            }
        }
        return $tree;
    }


    /**
     * 获取所有节点
     * @return array
     */
    protected function getAllPermission()
    {
        $list_collection = Permission::where(compact('parent_id'))->get()->toArray();
        return array_column($list_collection, null, 'id');
    }

    protected function getSelectedPermission()
    {
        $role_id = request()->get('role_id');
        $list_permission = Role::find($role_id)->permissions->toArray();
        return array_column($list_permission, 'name');
    }

    /**
     * 递归获取子级的权限节点
     */
    public function recursiveList()
    {
        $list_collection = $this->getAllPermission();
        return $this->generateTree($list_collection);
    }

    /**
     * 生成无限树
     * @param $items
     * @return array
     */
    public function generateTree($items)
    {
        $tree = array();
        foreach ($items as $item) {
            $items[$item['id']]['title'] = $items[$item['id']]['name'];
            $items[$item['id']]['expanded'] = true;
            if (isset($items[$item['parent_id']])) {
                $items[$item['parent_id']]['children'][] = &$items[$item['id']];
            } else {
                $tree[] = &$items[$item['id']];
            }
        }
        return $tree;
    }


    /**
     * API 编辑权限
     * @throws \Exception
     */
    public function update()
    {
        // 校验参数
        $this->verifyParamsForEdit();

        // 编辑权限
        $this->EditPermission();
    }

    /**
     * 编辑权限
     */
    protected function EditPermission()
    {
        // 生成参数
        $params = $this->genParamsForEdit();

        $id = request()->post('permission_id');
        return Permission::where(compact('id'))->update($params);
    }

    /**
     * 生成参数
     * @return array
     */
    protected function genParamsForEdit()
    {
        // 基本参数
        $base_params = $this->genBaseParamsForEdit();

        // 父级id
        $parent_params = $this->genParentIdForEdit();
        return array_merge($base_params, $parent_params);
    }

    protected function genParentIdForEdit()
    {
        $name = request()->post('parent_name');
        $permission_parent = Permission::where(compact('name'))->first();
        $parent_id = !!$permission_parent ? $permission_parent->id : 0;
        return compact('parent_id');
    }


    /**
     * 基本参数
     * @return array
     */
    protected function genBaseParamsForEdit()
    {
        return request()->only(['model', 'name', 'slug', 'description']);
    }



    /**
     * 为编辑权限校验参数
     * @throws \Exception
     */
    protected function verifyParamsForEdit()
    {
        // 校验选择的字段的是否存在
        $this->verifyExistForEdit();

        // 校验slug的唯一性
        $this->verifyUniqueSlugForEdit();

        // 校验name的唯一性
        $this->verifyUniqueNameForEdit();
    }


    /**
     * 为编辑权限校验name的唯一性
     * @throws \Exception
     */
    protected function verifyUniqueNameForEdit()
    {
        $name = request()->post('name');
        $permission_id = request()->post('permission_id');
        $id = [
            '!=', $permission_id
        ];
        $exist = Permission::where(compact('name', 'id'))->first();
        if ($exist) {
            throw new \Exception('您所填写的name已经被占用，请重新选择');
        }
    }

    /**
     * 为编辑权限校验slug的唯一性
     * @throws \Exception
     */
    protected function verifyUniqueSlugForEdit()
    {
        $slug = request()->post('slug');
        $permission_id = request()->post('permission_id');
        $id = [
            '!=', $permission_id
        ];
        $exist = Permission::where(compact('slug', 'id'))->first();
        if ($exist) {
            throw new \Exception('您所填写的slug已经被占用，请重新选择');
        }
    }

    /**
     * 为编辑权限校验参数是否存在
     * @throws \Exception
     */
    protected function verifyExistForEdit()
    {
        // name slug level
        $name = request()->post('name');
        if (!$name) {
            throw new \Exception('请填写权限名称');
        }

        $slug = request()->post('slug');
        if (!$slug) {
            throw new \Exception('请填写唯一标识');
        }

        $permission_id = request()->post('permission_id');
        if (!$permission_id) {
            throw new \Exception('请传递要编辑的权限的ID');
        }

        $parent_name = request()->post('parent_name');
        if (!$parent_name) {
            throw new \Exception('请选择父级权限');
        }
    }

    /**
     * 存取权限
     * @throws \Exception
     */
    public function store()
    {
        // 校验参数
        $this->verifyParamsForStore();

        // 存取权限
        return $this->storePermission();
    }


    /**
     * 为存权限校验参数
     * @throws \Exception
     */
    protected function verifyParamsForStore()
    {
        // 校验选择的字段的是否存在
        $this->verifyExistForSore();

        // 校验slug的唯一性
        $this->verifyUniqueSlugForStore();

        // 校验Name的唯一性
        $this->verifyUniqueNameForStore();
    }

    /**
     * 校验Name的唯一性
     * @throws \Exception
     */
    protected function verifyUniqueNameForStore()
    {
        $name = request()->post('name');
        $exists = Permission::where(compact('name'))->first();
        if ($exists) {
            throw new \Exception('您所填写的name已经被占用，请重新填写');
        }
    }

    /**
     * 为存取权限校验slug的唯一性
     * @throws \Exception
     */
    protected function verifyUniqueSlugForStore()
    {
        $slug = request()->post('slug');
        $exist = Permission::where(compact('slug'))->first();
        if ($exist) {
            throw new \Exception('您所填写的slug已经被占用，请重新填写');
        }
    }

    /**
     * 为存取权限校验参数是否存在
     * @throws \Exception
     */
    protected function verifyExistForSore()
    {
        // name slug level
        $name = request()->post('name');
        if (!$name) {
            throw new \Exception('请填写权限名称');
        }

        $slug = request()->post('slug');
        if (!$slug) {
            throw new \Exception('请填写唯一标识');
        }

        $parent_name = request()->post('parent_name');
        if (!$parent_name) {
            throw new \Exception('网络故障，请稍后再试');
        }
    }

    /**
     * 存取权限
     * @return boolean
     */
    protected function storePermission()
    {
        // 获取参数
        $params = $this->genParamsForStore();
        return Permission::create($params);
    }

    /**
     * 为存取
     * @return array
     */
    protected function genParamsForStore()
    {
        $params = request()->only(['model', 'name', 'slug', 'description']);

        // 父级的ID
        $name = request()->post('parent_name');
        $parent_permission = Permission::where(compact('name'))->first();
        $parent_id = $parent_permission ? $parent_permission['id'] : 0;
        return array_merge($params, compact('parent_id'));
    }


    /**
     * 权限列表
     */
    public function list()
    {
        // 参数列表
        $where = $this->genConditionForList();

        return Permission::where($where)->get();
    }

    protected function genConditionForList()
    {
        // 权限列表
        $id = request()->get('permission_id');
        if (!$id) {
            return [];
        }

        return compact('id');
    }

}