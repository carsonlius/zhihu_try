<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $repository_user;

    /**
     * UserController constructor.
     * @param $repository_user
     */
    public function __construct(UserRepository $repository_user)
    {
        $this->repository_user = $repository_user;
    }

    /**
     * 处理用户头像处理界面
     */
    public function avatar()
    {
        return view('users.avatar');
    }

    /**
     * 保存用户头像
     * @return \Illuminate\Http\JsonResponse
     */
    public function avatarUpload()
    {
        try {
            $this->repository_user->avatarUpload();

            $msg = '更新上传成功';
            $status = 0;
            $img_url = user()->avatar;
            return response()->json(compact('status', 'msg', 'img_url'));
        } catch (\Exception $e) {
            $status = 500;
            $msg = $e->getMessage();
            return response()->json(compact('status', 'msg'));
        }
    }
}
