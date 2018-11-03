<?php

namespace App\Repositories;

use EasyWeChat\Kernel\Messages\Article;
use EasyWeChat\Kernel\Messages\Video;
use EasyWeChat\OfficialAccount\Application;

class MaterialRepository
{
    private $wechat;

    /**
     * MaterialRepository constructor.
     * @param $wechat
     */
    public function __construct(Application $wechat)
    {
        $this->wechat = $wechat;
    }

    public function sendCustomer()
    {
        $openId = 'oweQV1g3B-wOoSwF1qvWzfiT78wE';
        $message = new Video('YUZNEhpGLlvPSHqeoS_VrJC0a6dofidtRx530GldK6k');
        return $this->wechat->customer_service->message($message)->to($openId)->send();
    }

    /**
     * 获取特定的素材
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \Exception
     */
    public function show()
    {
        // 检查条件
        $this->validateParamsForShow();

        // 获取素材
        return $this->getMaterialForShow();
    }

    /**
     * 获取素材
     * @return mixed
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     */
    private function getMaterialForShow()
    {
        $media_id = trim(request()->input('media_id'));
        return $this->wechat->material->get($media_id);
    }

    /**
     * 检查条件
     * @throws \Exception
     */
    private function validateParamsForShow()
    {
        $media_id = request()->get('media_id', '');
        if (!trim($media_id)) {
            throw new \Exception('请输入media_id参数');
        }
    }

    /**
     * 上传图文消息
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     */
    public function news()
    {
        // 上传缩略图
        $info_thumb = $this->thumb();

        // 上传图文
        return $this->uploadNews($info_thumb->media_id);
    }

    /**
     * 缩略图的media
     * @param string $media_id
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     */
    protected function uploadNews(string $media_id)
    {
        $article = new Article([
            'title' => '布拉德 - 影史上最有味道的男人',
            'thumb_media_id' => $media_id,
            'author' => 'carsonlius', // 作者
            'show_cover' => 1, // 是否在文章内容显示封面图片
            'digest' => '布拉德·皮特（Brad Pitt），1963年12月18日出生于美国俄克拉何马州，美国电影演员、制片人。1987年，皮特以临时演员的身份参加了他的第一部电影《无主地》的 ...',
            'content' => '布拉德·皮特（Brad Pitt），1963年12月18日出生于美国俄克拉何马州，美国电影演员、制片人。
1987年，皮特以临时演员的身份参加了他的第一部电影《无主地》的拍摄。1993年，皮特出演《加州杀手》，并凭此片获得威尼斯电影节最佳男演员奖 [1]  。1995年，皮特出演《十二只猴子》，并凭借片中的表演获得了奥斯卡最佳男配角奖的提名。2002年，布拉德·皮特创立了B计划影业 [2]  。2005年，皮特与安吉丽娜·朱莉合作了《史密斯夫妇》，两人相恋。2007年，皮特凭《神枪手之死》第二次获得威尼斯电影节最佳男演员奖。2008年，皮特凭借《返老还童》获得第81届奥斯卡最佳男主角的提名。2010年，由他制片并主演的《点球成金》为他带来又一个奥斯卡最佳男主角提名以及最佳影片提名。2011年，他主演了电影《温柔的杀戮》。2013年，皮特主演的《末日之战》上映。2016年，布拉德·皮特主演的好莱坞爱情谍战片《间谍同盟》于11月30日在中国上映 [3-4]  。
2016年，布拉德·皮特筹拍新片《他想要月亮》。 [5]  2016年，布拉德·皮特以3，150万美元（约2.4亿港元）排《福布斯》全球十大最高收入男星第十位。 [6] 
据CNN消息，2016年9月19日，安吉丽娜·朱莉申请和布拉德·皮特离婚。',
            'source_url' => 'https://baike.baidu.com/item/%E5%B8%83%E6%8B%89%E5%BE%B7%C2%B7%E7%9A%AE%E7%89%B9/81288',
        ]);
        return $this->wechat->material->uploadArticle($article);

    }

    /**
     * 上传缩略图
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     */
    public function thumb()
    {
        $path_thumb = public_path() . '/storage/wechat/image/bulade.jpg';
        return $this->wechat->material->uploadThumb($path_thumb);
    }

    /**
     * 获取素材列表
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     */
    public function list()
    {
       $list_image = $this->wechat->material->list('image', 0, 1000)->toArray();
       $list_video = $this->wechat->material->list('video', 0, 1000)->toArray();
       $list_voice = $this->wechat->material->list('voice', 0, 1000)->toArray();
       $list_news = $this->wechat->material->list('news', 0, 1000)->toArray();
       return compact('list_image', 'list_news', 'list_video', 'list_voice');
    }

    /**
     * 本地图片上传到微信服务器
     * @return array
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     */
    public function image()
    {
        $path_image = public_path() . '/storage/wechat/image/beautiful.jpg';
        return $this->wechat->material->uploadImage($path_image)->toArray();
    }

    /**
     * 上传音频
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     */
    public function audio()
    {
        $path_music = public_path() . '/storage/wechat/audio/需求.mp3';
        return $this->wechat->material->uploadVoice($path_music);
    }

    /**
     * 上传视频
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     */
    public function video()
    {
        $path_video = public_path() . '/storage/wechat/video/21533501111880c6af750442.mp4';
        $title = request()->get('title', '同事');
        $desc = request()->get('desc', '同事');
        return $this->wechat->material->uploadVideo($path_video, $title, $desc);
    }
}