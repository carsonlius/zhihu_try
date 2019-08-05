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
     * 更新音乐期刊的播放地址
     */
    public function updateMusic()
    {
        try {
            $periodical= $this->repository->updateMusic();
            return $this->response(compact('periodical'));
        } catch (CustomException $e) {
            return $this->setStatus(1478)->responseError($e->getMessage());
        } catch (\Exception $e) {
            return $this->setStatus(1478)->responseError($e->getMessage() .  ' at line ' . $e->getLine() . ' at file ' . $e->getFile());
        }
    }

    /**
     * 上一期刊
     * @param $periodical_index
     * @return \Illuminate\Http\JsonResponse
     */
    public function prevPage($periodical_index)
    {
        try {
            $periodical= $this->repository->prevPage($periodical_index);
            return $this->response(compact('periodical'));
        } catch (CustomException $e) {
            return $this->setStatus(1478)->responseError($e->getMessage());
        } catch (\Exception $e) {
            return $this->setStatus(1478)->responseError($e->getMessage() .  ' at line ' . $e->getLine() . ' at file ' . $e->getFile());
        }
    }

    /**
     * 下一期刊
     * @return \Illuminate\Http\JsonResponse
     */
    public function nextPage($periodical_index)
    {
        try {
            $periodical= $this->repository->nextPage($periodical_index);
            return $this->response(compact('periodical'));
        } catch (CustomException $e) {
            return $this->setStatus(1478)->responseError($e->getMessage());
        } catch (\Exception $e) {
            return $this->setStatus(1478)->responseError($e->getMessage() .  ' at line ' . $e->getLine() . ' at file ' . $e->getFile());
        }
    }


    /**
     * 最新的一期期刊
     */
    public function latest()
    {
        try {
            $periodical= $this->repository->latest();
            return $this->response(compact('periodical'));
        } catch (CustomException $e) {
            return $this->setStatus(1478)->responseError($e->getMessage());
        } catch (\Exception $e) {
            return $this->setStatus(1478)->responseError($e->getMessage() .  ' at line ' . $e->getLine() . ' at file ' . $e->getFile());
        }
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
