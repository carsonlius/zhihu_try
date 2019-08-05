<?php

namespace App\Http\Controllers;

use App\Http\Repositories\MiniProgramRepository;
use App\Http\TraitHelper\CustomException;
use App\Http\TraitHelper\ResponseTrait;

class MiniProgramController extends Controller
{
    use ResponseTrait;

    private $_repository;

    /**
     * MiniProgramController constructor.
     * @param $_repository
     */
    public function __construct(MiniProgramRepository $_repository)
    {
        $this->_repository = $_repository;
    }

    /**
     * 点赞操作
     * @return \Illuminate\Http\JsonResponse
     */
    public function like()
    {
        try {
            $this->_repository->like();
            return $this->response(['msg' => '操作成功']);
        } catch (CustomException $e) {
            return $this->setStatus(1478)->responseError($e->getMessage());
        } catch (\Exception $e) {
            return $this->setStatus(1478)->responseError($e->getMessage() . ' at line ' . $e->getLine() . ' at file ' . $e->getFile());
        }
    }

    /**
     * 生成私有token
     * @return \Illuminate\Http\JsonResponse
     */
    public function genPersonalToken()
    {
        try {
            $data = $this->_repository->genPersonalToken();
            return $this->response(compact('data'));
        } catch (CustomException $e) {
            return $this->setStatus(1478)->responseError($e->getMessage());
        } catch (\Exception $e) {
            return $this->setStatus(1478)->responseError($e->getMessage() . ' at line ' . $e->getLine() . ' at file ' . $e->getFile());
        }
    }

    /**
     * 登陆操作
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        try {
            $this->_repository->login();
            return $this->response(['msg' =>  '登陆成功']);
        } catch (CustomException $e) {
            return $this->setStatus(1478)->responseError($e->getMessage());
        } catch (\Exception $e) {
            return $this->setStatus(1478)->responseError($e->getMessage() . ' at line ' . $e->getLine() . ' at file ' . $e->getFile());
        }
    }

    /**
     * 获取会话密钥
     * @return \Illuminate\Http\JsonResponse
     */
    public function codeToSession()
    {
        try {
            $data = $this->_repository->codeToSession();
            return $this->response(compact('data'));
        } catch (CustomException $e) {
            return $this->setStatus(1478)->responseError($e->getMessage());
        } catch (\Exception $e) {
            return $this->setStatus(1478)->responseError($e->getMessage() . ' at line ' . $e->getLine() . ' at file ' . $e->getFile());
        }
    }

    /**
     * 解密敏感信息
     * @return \Illuminate\Http\JsonResponse
     */
    public function decode()
    {
        try {
            $data = $this->_repository->decode();
            return $this->response(compact('data'));
        } catch (CustomException $e) {
            return $this->setStatus(1478)->responseError($e->getMessage());
        } catch (\Exception $e) {
            return $this->setStatus(1478)->responseError($e->getMessage() . ' at line ' . $e->getLine() . ' at file ' . $e->getFile());
        }
    }
}
