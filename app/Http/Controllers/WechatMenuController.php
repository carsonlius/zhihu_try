<?php

namespace App\Http\Controllers;

use App\Http\TraitHelper\ResponseTrait;
use App\Repositories\WechatMenuRepository;
use EasyWeChat\Factory;
use EasyWeChat\Work\Application;

class WechatMenuController extends Controller
{
    use ResponseTrait;
    private $repository;

    /**
     * WechatMenuController constructor.
     * @param $repository
     */
    public function __construct(WechatMenuRepository $repository)
    {
        $this->repository = $repository;
    }

    public function create()
    {
        try {
            $response = $this->repository->create();
            $msg = '消息群发成功';
            return $this->response(compact('msg', 'response'));
        } catch (\Exception $e) {
            return $this->setStatus(1478)->responseError($e->getMessage());
        }
    }

    public function test(Application $wechat_work)
    {
//        dd(config('wechat.work.default'));
//        $wechat_work = \EasyWeChat::work(); // 企业微信
//        $wechat_work = Factory::work(config('wechat.work.default'));
//        $wechat_work = app('wechat.work');

        return $wechat_work->messenger->ofAgent(1000015)->message('企业微信的文档，真的是刚刚起步! 还是要看源码! 社区威武！')->send();
//        return $wechat_work->broadcasting->sendText('测试信息？', 12);
    }
}
