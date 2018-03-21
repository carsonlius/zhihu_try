<?php

namespace App\Http\Controllers;

use App\User;

class EmailConfirmController extends Controller
{
    public function Activate($confirmation_token)
    {
        // 修改confirmation_token 防止多次点击
        if ($obj_user = User::where(compact('confirmation_token'))->first()) {
            $obj_user->confirmation_token = sha1(str_random(40));
            $obj_user->is_active = 1;
            $obj_user->save();
            \Session::flash('email_confirm', '账户已经激活');


            \Auth::login($obj_user);
            return redirect('/');
        }

        \Session::flash('email_confirm', '激活失败,请稍后再试一试');
        return redirect('home');
    }
}
