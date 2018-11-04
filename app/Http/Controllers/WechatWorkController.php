<?php

namespace App\Http\Controllers;

use App\Http\Repositories\WechatWorkRepository;
use App\Http\TraitHelper\ResponseTrait;

class WechatWorkController extends Controller
{
    use ResponseTrait;
    private $repository;

    /**
     * WechatWorkController constructor.
     * @param $wechat_work
     */
    public function __construct(WechatWorkRepository $wechat_work)
    {
        $this->repository = $wechat_work;
    }

    public function sendTag()
    {
        try {
            $response = $this->repository->sendTag();
            $msg = '消息群发成功';
            return $this->response(compact('msg', 'response'));
        } catch (\Exception $e) {
            return $this->setStatus(1478)->responseError($e->getMessage());
        }
    }
}
