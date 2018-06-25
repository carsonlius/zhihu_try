<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class FollowersController extends Controller
{

    protected $user_repository;

    public function __construct(UserRepository $user_repository)
    {
        $this->user_repository = $user_repository;
    }


    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $this->user_repository->byId($request->get('user'));

        // 登陆用户的ID
        $user_login_id = \Auth::guard('api')->user()->id;

        // 如果登陆的用户是本身的话  则自然是关注的
        if ($user_login_id == $request->get('user')) {
            $followed = true;
        } else {
            // 登陆用户是否是关注了创建了问题的用户
            $followed = $user->followers->contains('id', $user_login_id);
        }
        $status = 0;

        return response()->json(compact('followed', 'status'));
    }

    /**
     * 开关控制当前用户是否继续关注某个用户
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function follow(Request $request)
    {
        $user_created = $request->post('user');
        $response = $this->user_repository->follow($user_created);
        return response()->json($response);
    }
}
