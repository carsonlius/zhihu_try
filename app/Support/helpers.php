<?php

if (!function_exists('user')) {
    /**
     * 返回当前登陆的用户
     * @param string $driver
     * @return mixed
     */
    function user($driver = 'web')
    {
        return app('auth')->guard($driver)->user();
    }

}