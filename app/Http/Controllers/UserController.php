<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use App\User;
use http\Env\Response;
use phpDocumentor\Reflection\Types\Compound;

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
     * 编辑用户所属的角色
     */
    public function updateRole()
    {
        try {
            $status = 0;
            $this->repository_user->updateRole();
            return response()->json(compact('status'));
        } catch (\Exception $e) {
            $status = 4178;
            $msg = $e->getMessage();
            return response()->json(compact('status', 'msg'));
        }
    }

    /**
     * 分配角色 view
     */
    public function roleAssign()
    {
        try {
            // 检查参数 then return back
            $list_params = $this->repository_user->roleAssign();

            return view('users.role')->with($list_params);
        } catch (\Exception  $e) {
            flash($e->getMessage())->error();
            return redirect('/Role/user');
        }

    }

    /**
     * 用户角色列表 view
     */
    public function role()
    {
        return view('users.index');
    }

    /**
     * 用户全量列表
     */
    public function index()
    {
        try {
            $status = 0;
            $list_user = User::all();
            return response()->json(compact('status', 'list_user'));
        } catch (\Exception $e) {
            $status = 1478;
            $msg = $e->getMessage();
            return response()->json(compact('status', 'msg'));
        }

    }

    /**
     * 用户列表
     * @return \Illuminate\Http\JsonResponse
     */
    public function list()
    {
        try {
            $status = 0;
            $list_user = $this->repository_user->list();
            return response()->json(compact('status', 'list_user'));
        }catch (\Exception $e){
            $status = 1478;
            $msg = $e->getMessage();
            return response()->json(compact('status', 'msg'));
        }
    }

    /**
     * 用户设置列表
     */
    public function settingList()
    {
        $user = user();
        return view('users.setting')->with(compact('user'));
    }

    /**
     * 更新用户设置
     */
    public function update()
    {
        try {
            $response = user('api')->settings()->update(request()->all());
            if ($response == false) {
                throw new \Exception('更新配置失败');
            }
            $status = 0;
            $msg = '更新配置成功';
            return response()->json(compact('msg', 'status'));
        } catch (\Exception $e) {
            $status = 1478;
            $msg = $e->getMessage();
            return response()->json(compact('msg', 'status'));
        }

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
            $img_url = $this->repository_user->avatarUpload();

            $msg = '更新上传成功';
            $status = 0;
            return response()->json(compact('status', 'msg', 'img_url'));
        } catch (\Exception $e) {
            $status = 1478;
            $msg = $e->getMessage();
            return response()->json(compact('status', 'msg'));
        }
    }
}
