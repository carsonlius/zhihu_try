<?php

namespace App\Http\Controllers;

use App\Http\TraitHelper\ResponseTrait;
use App\Repositories\MaterialRepository;

class MaterialController extends Controller
{
    use ResponseTrait;
    private $repository;

    /**
     * MaterialController constructor.
     * @param $repository
     */
    public function __construct(MaterialRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * 客服消息(主动发送)
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendCustomer()
    {
        try {
            $response = $this->repository->sendCustomer();
            $msg = '客服消息';
            return $this->response(compact('msg', 'response'));
        } catch (\Exception $e) {
            return $this->setStatus(1478)->responseError($e->getMessage());
        }
    }

    /**
     * 本地图片上传到服务器
     */
    public function image()
    {
        try {
            $response = $this->repository->image();
            $msg = '上传到微信服务器成功';
            return $this->response(compact('msg', 'response'));
        } catch (\Exception $e) {
            return $this->setStatus(1478)->responseError($e->getMessage());
        }
    }

    /**
     * 上传音频
     * @return \Illuminate\Http\JsonResponse
     */
    public function audio()
    {
        try {
            $response = $this->repository->audio();
            $msg = '上传到微信服务器成功';
            return $this->response(compact('msg', 'response'));
        } catch (\Exception $e) {
            return $this->setStatus(1478)->responseError($e->getMessage());
        }
    }

    /**
     * 上传视频
     */
    public function video()
    {
        try {
            $response = $this->repository->video();
            $msg = '上传到微信服务器成功';
            return $this->response(compact( 'msg', 'response'));
        } catch (\Exception $e) {
            return $this->setStatus(1478)->responseError($e->getMessage());
        }
    }

    /**
     * 获取特定的material
     */
    public function show()
    {
        try {
            $material = $this->repository->show();
            return $this->response(compact( 'material'));
        } catch (\Exception $e) {
            return $this->setStatus(1478)->responseError($e->getMessage());
        }
    }

    /**
     * 上传图文消息
     */
    public function news()
    {
        try {
            $news = $this->repository->news();
            $msg = '上传到服务器成功';
            return $this->response(compact('news', 'msg'));
        } catch (\Exception $e) {
            return $this->setStatus(1478)->responseError($e->getMessage());
        }
    }

    /**
     * 素材列表
     */
    public function list()
    {
        try {
            $list_material = $this->repository->list();
            return $this->response(compact('list_material'));
        } catch (\Exception $e) {
            return $this->setStatus(1478)->responseError($e->getMessage());
        }
    }
}
