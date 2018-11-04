<?php

namespace App\Http\Controllers;

use App\Http\TraitHelper\ResponseTrait;
use App\Repositories\WechatMenuRepository;

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
}
