<?php

namespace App\Http\Repositories;

class PasswordRepository
{
    /**
     * 更新密码
     * @throws \Exception
     */
    public function update()
    {
        // 判断原始密码是否正确
        $old_password = request()->get('old_password');
        if (!\Hash::check($old_password, user()->password)) {
            flash('原始密码不正确')->error();
            throw new \Exception('原始密码不正确');
        }

        user()->password = \Hash::make(request()->get('password'));
        user()->save();
        flash('密码修改成功')->success();
    }
}