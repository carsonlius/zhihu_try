<?php

namespace App\Http\Repositories;

use EasyWeChat\Kernel\Messages\Image;
use EasyWeChat\Kernel\Messages\News;
use EasyWeChat\Kernel\Messages\Transfer;
use EasyWeChat\Kernel\Messages\Voice;
use EasyWeChat\OfficialAccount\Application;
use Illuminate\Support\Facades\Log;

class WeChatRepository
{
    private $wechat;

    /**
     * WeChatRepository constructor.
     * @param $wechat
     */
    public function __construct(Application $wechat)
    {
        $this->wechat = $wechat;
    }

    /**
     * 消息群发
     * @return mixed
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     */
    public function broadcasting()
    {
        $text_preview = '可能你听说了双 11 并不省钱的道理，也看到了各种比价软件在这期间纷纷 “无法使用” 的消息。

但当你看到满减红包、抓猫猫得红包以及各种定金抵扣、膨胀等促销活动后，可能还是忍不住剁手了，并且相信自己多少还是得到了点实惠。

你的行动化成了双 11 各大电商销售额里的数字。根据星途公司公布的数据，今年双 11 当天，天猫累计成交额 1682 亿元，京东达到了 543.5 亿元，网易考拉称在双十一开售的第 78 分钟，销售额达到了去年的两倍。

对于你自己，可能真的只是买了一堆并没有省到钱的东西，而且一年比一年更贵了。

中国消费者协会连续两年对双 11 的销售情况做了跟踪调查，发现去年双 11 真正便宜了的商品占 28%，今年只有 22%了。';
        $this->wechat->broadcasting->previewText($text_preview,  'oweQV1g3B-wOoSwF1qvWzfiT78wE');

//        return $this->wechat->broadcasting->sendVideo('YUZNEhpGLlvPSHqeoS_VrJC0a6dofidtRx530GldK6k', 100);
//        return $this->wechat->broadcasting->sendVoice('YUZNEhpGLlvPSHqeoS_VrP-Q04lrw_rQKhUFb553lcA', 100);
//        return $this->wechat->broadcasting->sendVoice('YUZNEhpGLlvPSHqeoS_VrJC0a6dofidtRx530GldK6k', 'oweQV1g3B-wOoSwF1qvWzfiT78wE');
//        return $this->wechat->broadcasting->sendNews('YUZNEhpGLlvPSHqeoS_VrI-F1jBJzdHWu5UOs98XhlA', 'oweQV1g3B-wOoSwF1qvWzfiT78wE');
//        return $this->wechat->broadcasting->sendImage('YUZNEhpGLlvPSHqeoS_VrJ2HyOqmqIUcxd2NSxP1zCE', 'oweQV1g3B-wOoSwF1qvWzfiT78wE');
//        return $this->wechat->broadcasting->sendImage('YUZNEhpGLlvPSHqeoS_VrJ2HyOqmqIUcxd2NSxP1zCE', 100);
        return $this->wechat->broadcasting->sendText('妹妹啊， 最近学习怎么样? 成绩不需要太好，但是学还是要升的! 天气冷了，冬天的衣服有吗？', 100);
    }


    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \EasyWeChat\Kernel\Exceptions\BadRequestException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \ReflectionException
     */
    public function serve()
    {
        Log::info('request arrived.'); # 注意：Log 为 Laravel 组件，所以它记的日志去 Laravel 日志看，而不是 EasyWeChat 日志

        $this->wechat->server->push(function ($message) {
            Log::debug('记录每次微信用户传入的信息', compact('message'));

            switch ($message['MsgType']) {
                case 'event':
                    return '收到事件消息';
                    break;
                case 'text':
                    $user_from = $this->wechat->user->get($message['FromUserName'])->nickname;
                    return '不需要回信息, 暂时是没有办法交互的！ ' . $user_from;
                    break;
                case 'image':
                    $obj_message = new Image('YUZNEhpGLlvPSHqeoS_VrDlXMMREQ_AAPmLM-wxn7qg');
                    $this->wechat->customer_service->message($obj_message)->to($message->FromUserName)->send();
                    return '收到图片消息';
                    break;
                case 'voice':
                    return new Voice('YUZNEhpGLlvPSHqeoS_VrP-Q04lrw_rQKhUFb553lcA');
                    break;
                case 'video':
                    return '收到视频消息';
                    break;
                case 'location':
                    return '收到坐标消息';
                    break;
                case 'link':
                    return '收到链接消息';
                    break;
                case 'file':
                    return '收到文件消息';
                // ... 其它消息
                default:
                    return '收到其它消息';
                    break;
            }
        });

        Log::info('return response.');

        return $this->wechat->server->serve();
    }

    private function event()
    {


    }

}