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
     * 文件上传
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload()
    {
        try {
            $data = $this->repository->upload();
            return $this->response(compact('data'));
        } catch (CustomException $e) {
            return $this->setStatus(1478)->responseError($e->getMessage());
        } catch (\Exception $e) {
            return $this->setStatus(1478)->responseError($e->getMessage() .  ' at line ' . $e->getLine() . ' at file ' . $e->getFile());
        }

    }
}
