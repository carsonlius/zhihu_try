<?php
namespace App\Http\Repositories;

use EasyWeChat\Work\Application;

class WechatWorkRepository
{
    private $wechat_work;

    /**
     * WechatWorkController constructor.
     * @param $wechat_work
     */
    public function __construct(Application $wechat_work)
    {
        $this->wechat_work = $wechat_work;
    }

    /**
     * @return mixed
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     */
    public function sendTag()
    {
        return $this->wechat_work->messenger
            ->message('Congratulation！')
            ->ofAgent(env('WECHAT_WORK_AGENT_ID'))
            ->toTag(13)
            ->send();
    }
}