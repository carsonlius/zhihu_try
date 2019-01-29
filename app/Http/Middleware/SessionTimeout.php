<?php

namespace App\Http\Middleware;

use Closure;

class SessionTimeout
{
    /*
     * 用户上次活动的时间
     * */
    private $key_session_last_active = 'last_active_time';

    /*
     * 保持时间
     * */
    private $time_decay = 7200;

    private $list_except_path = [
        'broadcasting/auth'
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // 没有登陆则继续下面的请求 &&  pusher service path 排除
        $path = $request->path();
        if (!auth()->check() || in_array($path, $this->list_except_path)) {
            return $next($request);
        }

        // 如果上次缓存有存值
        if (session()->has($this->key_session_last_active)) {
            // 如果超过session的衰变期 && 还在登录状态
            $time_decay = time() - session($this->key_session_last_active);
            if ($time_decay > $this->time_decay) {
                $email = auth()->user()->email;
                auth()->logout();
                session()->forget($this->key_session_last_active);

                flash('您好，系统监测到您2个小时未操作，请重新登陆!')->warning();
                return redirect('login')->withInput(compact('email'));
            }
        }

        // logout操作
        if ($this->determineLogoutAction()){
            session()->has($this->key_session_last_active) && session()->forget($this->key_session_last_active);
        } else {
            session()->put($this->key_session_last_active, time());
        }

        return $next($request);
    }

    /**
     * 是否是logout action
     * @return bool
     */
    private function determineLogoutAction(): bool
    {
        $route_name = request()->route()->getName();
        return $route_name === 'logout';
    }
}
