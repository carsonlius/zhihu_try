<?php

namespace App\Http\Controllers;

use App\Http\Repositories\WeChatUserRepository;
use App\Http\TraitHelper\ResponseTrait;

class WeChatUserController extends Controller
{
    use ResponseTrait;
    protected $repository;

    /**
     * WeChatUserController constructor.
     * @param $repository
     */
    public function __construct(WeChatUserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * 关注者列表
     * @return \Illuminate\Http\JsonResponse
     */
    public function list()
    {
        try {
            $list_weChat_user = $this->repository->list();
            return $this->response(compact('list_weChat_user'));
        } catch (\Exception $e) {
            return $this->setStatus(1478)->responseError($e->getMessage());
        }
    }

    /**
     * 关注者详情
     * @return \Illuminate\Http\JsonResponse
     */
    public function show()
    {
        try {
            $wechat_user = $this->repository->show();
            return $this->response(compact('wechat_user'));
        } catch (\Exception $e) {
            return $this->setStatus(1478)->responseError($e->getMessage());
        }
    }

    /**
     *  关注者更新
     */
    public function update()
    {
        try {
            $this->repository->update();
            $msg = '备注更改成功';
            return $this->response(compact('msg'));
        } catch (\Exception $e) {
            return $this->setStatus(1478)->responseError($e->getMessage());
        }
    }
}
