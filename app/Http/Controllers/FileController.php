<?php

namespace App\Http\Controllers;

use App\Http\Repositories\FileRepository;
use App\Http\TraitHelper\CustomException;
use App\Http\TraitHelper\ResponseTrait;
use Illuminate\Http\Request;

class FileController extends Controller
{
    use ResponseTrait;
    private $repository;

    /**
     * FileController constructor.
     * @param $repository
     */
    public function __construct(FileRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * 上传单个文件
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadSingle()
    {
        try {
            $data = $this->repository->uploadSingle();
            return $this->response(compact('data'));
        } catch (CustomException $e) {
            return $this->setStatus(1478)->responseError($e->getMessage());
        } catch (\Exception $e) {
            return $this->setStatus(1478)->responseError($e->getMessage() .  ' at line ' . $e->getLine() . ' at file ' . $e->getFile());
        }
    }

    /**
     * 删除单个文件
     */
    public function deleteSingle()
    {
        try {
            $this->repository->deleteSingle();
            $msg = '删除成功';
            return $this->response(compact('msg'));
        } catch (CustomException $e) {
            return $this->setStatus(1478)->responseError($e->getMessage());
        } catch (\Exception $e) {
            return $this->setStatus(1478)->responseError($e->getMessage() .  ' at line ' . $e->getLine() . ' at file ' . $e->getFile());
        }
    }
}
