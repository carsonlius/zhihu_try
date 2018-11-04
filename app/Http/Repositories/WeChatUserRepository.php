<?php

namespace App\Http\Repositories;

use EasyWeChat\OfficialAccount\Application;

class WeChatUserRepository
{
    private $wechat;

    /**
     * WeChatUserRepository constructor.
     * @param $wechat
     */
    public function __construct(Application $wechat)
    {
        $this->wechat = $wechat;
    }

    /**
     * 为用户设置标签
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     */
    public function setUserTag()
    {
        $open_ids = ['oweQV1t0aEI5HsHaYPGWLgGBVF0M'];

        return $this->wechat->user_tag->tagUsers($open_ids, 100);
    }

    /**
     * 新建标签
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     */
    public function tagCreate()
    {
        return $this->wechat->user_tag->create('管理员');
    }

    /**
     * 标签列表
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     */
    public function tagList()
    {
        return $this->wechat->user_tag->list();

    }

    /**
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     */
    public function update()
    {
        // TODO 校验参数

        // 更新
        $this->updateDo();
    }

    /**
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     */
    private function updateDo()
    {
        $openid = 'oweQV1g3B-wOoSwF1qvWzfiT78wE';
        $remark = '我的第' . mt_rand(1, 10) . '个关注者';
        $this->wechat->user->remark($openid, $remark);
    }


    /**
     * 关注公众号的用户列表
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     */
    public function list()
    {
        return $this->wechat->user->list();
    }

    /**
     * 选中特定的用户
     * @throws \Exception
     */
    public function show()
    {
        // 检测参数
//        $this->validateParamsForShow();

        // 获取选中的关注者
        return $this->getWeChatUser();
    }

    /**
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     */
    private function getWeChatUser()
    {
        $openid = trim(request()->get('openid'));
        $openid = 'oweQV1g3B-wOoSwF1qvWzfiT78wE';
        return $this->wechat->user->get($openid);
    }

    /**
     * 检测参数
     * @throws \Exception
     */
    private function validateParamsForShow()
    {
        $openid = request()->get('openid', '');
        if (!trim($openid)) {
            throw new \Exception('请输入openid');
        }
    }
}