<?php

namespace App\Http\TraitHelper;

trait ResponseTrait
{
    private $status = 0;

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * 设置status
     * @param int $status
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * 404 return
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseNotFound($message = 'Not Found')
    {
        return $this->setStatus(404)->responseError($message);
    }

    /**
     * 错误返回
     * @param  string $msg 错误信息
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseError($msg)
    {
        $status = $this->getStatus();
        $tip = 'failed';
        $errors = compact('tip', 'msg');
        return $this->response(compact('status', 'errors'));
    }

    /**
     * 回复信息(success or error)
     * @param array $response
     * @return \Illuminate\Http\JsonResponse
     */
    public function response(array $response)
    {
        if (!array_key_exists('status', $response)) {
            $status = $this->getStatus();
            $response = array_merge($response, compact('status'));
        }

        return response()->json($response);
    }
}