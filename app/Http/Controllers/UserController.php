<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * 处理用户头像
     */
    public function avatar()
    {
        return view('users.avatar');
    }
}
