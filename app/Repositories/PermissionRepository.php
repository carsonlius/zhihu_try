<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use PHPUnit\Runner\Exception;
use Ultraware\Roles\Models\Permission;

class PermissionRepository
{
    // 递归存取各个节点的信息
    protected $list_recursive;

    /**
     * 递归获取子级的权限节点
     */
    public function recursiveList()
    {
        $list_collection = Permission::where(compact('parent_id'))->get()->toArray();
        $list_collection = array_column($list_collection, null, 'id');
        return $this->generateTree($list_collection);
    }

    /**
     * 生成无限树
     * @param $items
     * @return array
     */
    public function generateTree($items){
        $tree = array();
        foreach($items as $item){
            $items[$item['id']]['title'] = $items[$item['id']]['name'];
            $items[$item['id']]['expanded'] = true;
            if(isset($items[$item['parent_id']])){

                $items[$item['parent_id']]['children'][] = &$items[$item['id']];
            }else{

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
        $params = request()->only(['model', 'name', 'slug', 'description', 'parent_id']);
        $params = array_map(function($item){
            return trim($item);
        }, $params);

        $id = request()->post('permission_id');
        return Permission::where(compact('id'))->update($params);
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
        if(!$permission_id) {
            throw new \Exception('请传递要编辑的权限的ID');
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
            throw new \Exception('您所填写的slug已经被占用，请重新选择');
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
    }

    /**
     * 存取权限
     * @return boolean
     */
    protected function storePermission()
    {
        $params = request()->only(['model', 'name', 'slug', 'description', 'parent_id']);

        // 去空格
        $params = array_map(function($item){
            return trim($item);
        }, $params);

        return Permission::create($params);
    }

    /**
     * 权限列表
     */
    public function list()
    {
        return Permission::all();
    }

}