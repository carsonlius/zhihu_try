<?php

namespace App\Http\Controllers;

use App\Http\Repositories\PeriodicalRepository;
use App\Http\TraitHelper\ResponseTrait;
use App\Http\TraitHelper\CustomException;
use App\Periodical;

class PeriodicalController extends Controller
{
    use ResponseTrait;

    private $repository;

    /**
     * PeriodicalController constructor.
     * @param $repository
     */
    public function __construct(PeriodicalRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * 更新
     * @return \Illuminate\Http\JsonResponse
     */
    public function update()
    {
        try {
            $periodical= $this->repository->update();
            return $this->response(compact('periodical'));
        } catch (CustomException $e) {
            return $this->setStatus(1478)->responseError($e->getMessage());
        } catch (\Exception $e) {
            return $this->setStatus(1478)->responseError($e->getMessage() .  ' at line ' . $e->getLine() . ' at file ' . $e->getFile());
        }
    }

    /**
     * 期刊列表
     */
    public function index()
    {
        return view('periodical.list');
    }

    /**
     * 新建期刊
     */
    public function create()
    {
        return view('periodical.create');
    }

    /**
     * 编辑模式
     * @param Periodical $periodical
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Periodical $periodical)
    {
        return view('periodical.edit')->with(['periodical' => json_encode($periodical->toArray(), JSON_UNESCAPED_UNICODE)]);
    }

    /**
     * 生成期刊
     */
    public function createDo()
    {
        try {
            $periodical= $this->repository->createDo();
            return $this->response(compact('periodical'));
        } catch (CustomException $e) {
            return $this->setStatus(1478)->responseError($e->getMessage());
        } catch (\Exception $e) {
            return $this->setStatus(1478)->responseError($e->getMessage() .  ' at line ' . $e->getLine() . ' at file ' . $e->getFile());
        }
    }

    /**
     * 期刊列表
     * @return \Illuminate\Http\JsonResponse
     */
    public function lists()
    {
        try {
            $data= $this->repository->lists();
            return $this->response(compact('data'));
        } catch (CustomException $e) {
            return $this->setStatus(1478)->responseError($e->getMessage());
        } catch (\Exception $e) {
            return $this->setStatus(1478)->responseError($e->getMessage() .  ' at line ' . $e->getLine() . ' at file ' . $e->getFile());
        }
    }
}
