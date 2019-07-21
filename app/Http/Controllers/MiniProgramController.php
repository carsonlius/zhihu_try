<?php

namespace App\Http\Controllers;

use App\Http\Repositories\MiniProgramRepository;
use App\Http\TraitHelper\CustomException;
use App\Http\TraitHelper\ResponseTrait;
use Illuminate\Http\Request;

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
