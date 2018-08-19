<?php

namespace App\Http\Middleware;

use Closure;

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
        \Menu::make('NavBar', function ($menu) {
            $menu->add('首页');
            $menu->add('问题广场', ' ')->nickname('question_list');
            $menu->item('question_list')->add('问题列表', 'Question/index');
            $menu->item('question_list')->add('创建问题', 'Question/create');
            $menu->item('question_list')->divide();

            $menu->add('系统设置', 'Role')->nickname('system_setting');
            $menu->item('system_setting')->add('新建角色', 'Role/create');
            $menu->item('system_setting')->add('角色管理', 'Role');

        });

        // 权限导航
        \Menu::makeOnce('NavPermission', function($menu){
            $menu->add('About',    []);
            $menu->about->add('Who We are', '#');
            $menu->get('about')->add('What', '#');
            $menu->item('about')->add('Our Goals', '#');
            $menu->what->add('List', '#');
        });



        return $next($request);
    }
}
