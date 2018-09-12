<?php

namespace App\Http\Middleware;

use Closure;
use Ultraware\Roles\Models\Permission;

class GenerateMenus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mix
     */
    public function handle($request, Closure $next)
    {
        // 全量的权限
        $list_permissions = $this->getLoginPermission();

        // 权限导航
        \Menu::makeOnce('NavPermission', function($menu) use ($list_permissions){
            array_walk($list_permissions, function($item) use ($menu){
                // 设置menu
               $this->setMenuItem($item, $menu);
            });
        });

        return $next($request);
    }

    /**
     * 获取登陆用户的权限列表
     * @return array
     */
    protected function getLoginPermission()
    {
        $list_permissions = $this->getPermissionList();

        // 过滤掉不属于登陆用户的权限
        return $this->filterPermissionWhichNotLogin($list_permissions);
    }

    /**
     * 过滤掉不输入登陆用户的权限
     * @param array $list_source_permissions 全量权限
     * @return array
     */
    protected function filterPermissionWhichNotLogin($list_source_permissions)
    {
        $list_permissions = [];
        array_walk($list_source_permissions, function ($item) use (&$list_permissions){
            // 登陆用户是否拥有某个权限
            $has_permission = $this->loginHasPermission($item);
            if (!$has_permission) {
                return true;
            }
            array_push($list_permissions, $item);
        });

        return $list_permissions;
    }

    /**
     * 登陆用户是否拥有某个权限
     * @param array $permission
     * @return boolean
     */
    protected function loginHasPermission($permission)
    {
        // 如果用户没有登陆 则只是展示首页
        if (!\Auth::check()) {
            return $permission['name'] === '首页';
        }

        return  \Auth::user()->hasPermission($permission);
    }

    /**
     * 设置menu
     * @param array  $item 权限节点
     * @param object $menu
     */
    protected function setMenuItem($item, $menu)
    {
        // 如果
        switch ($item['parent_id']) {
            case 0:
                // 一级菜单
                $menu->add($item['name'], ['route' => $item['slug'], 'id' => $item['id']]);
                break;
            default:
                // 父级
                $node_parent = $menu->find($item['parent_id']);
                $node_parent->add($item['name'], ['route' => $item['slug'], 'id' => $item['id']]);
        }
    }

    /**
     * 全量的权限
     * @return array
     */
    protected function getPermissionList()
    {
        $where  = ['is_show' => 'T'];
        $list_permissions = Permission::where($where)->get();
        return array_column($list_permissions->toArray(), null, 'id');
    }
}
