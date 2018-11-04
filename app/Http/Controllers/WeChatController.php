<?php

namespace App\Http\Controllers;

use App\Http\Repositories\WeChatRepository;
use App\Http\TraitHelper\ResponseTrait;
use EasyWeChat\Kernel\Messages\Image;
use EasyWeChat\Kernel\Messages\Voice;
use Illuminate\Support\Facades\Log;

class WeChatController extends Controller
{
    use ResponseTrait;
    private $repository;

    /**
     * WeChatController constructor.
     * @param $repository
     */
    public function __construct(WeChatRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * 处理微信的请求消息
     *
     * @return string
     */
    public function serve()
    {
        try {
            // 处理微信请求
            return $this->repository->serve();

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * 消息群发
     */
    public function broadcasting()
    {
        try {
            $response = $this->repository->broadcasting();
            $msg = '消息群发成功';
            return $this->response(compact('msg', 'response'));
        } catch (\Exception $e) {
            return $this->setStatus(1478)->responseError($e->getMessage());
        }
    }
}
