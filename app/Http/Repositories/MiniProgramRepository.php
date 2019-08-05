<?php

namespace App\Http\Repositories;

use App\Http\TraitHelper\{
    CurlTrait, CustomException
};
use App\MiniUtil\WXBizDataCrypt;
use App\WechatUser;
use Illuminate\Support\Facades\DB;

class MiniProgramRepository
{
    use CurlTrait;

    /** @var string  私有token的盐值 */
    private $slat_personal_token = 'sImp0aSI6ImY0NGM2MTI4Y2FhNjI3YmMzM';

    /**
     * 点赞操作
     * @throws CustomException
     */
    public function like()
    {
        // 校验参数
        $this->_validateParamForLike();

        // 点赞操作
        $this->likeDo();
    }

    /**
     * 点赞操作
     */
    private function likeDo()
    {
        request()->post('type') == 'periodical' && $this->_likeWhenPeriodical();
    }

    /**
     * 期刊点赞
     */
    private function _likeWhenPeriodical()
    {
        DB::transaction(function (){
            list($id, $increment, $like) = [
                request()->post('id'),
                request()->post('like') ? 1 : -1,
                request()->post('like'),
            ];

            // 期刊
            DB::table('periodicals')->where(compact('id'))->increment('fav_nums', $increment);

            // 期刊 用户点赞情况
            $params = [
                'periodical_id' => $id,
                'user_id' => auth('passport')->user()->id
            ];
            if ($like) {
                $this->_updateLike($params);
                return;
            } else {
                // 更新成取消点赞
                DB::table('periodical_likes')->where($params)->delete();
            }
        }, 3);
    }

    /**
     * 更新成点赞
     * @param array $params
     */
    private function _updateLike(array $params)
    {
       $exists = DB::table('periodical_likes')->where($params)->exists();
       if ($exists) {
           return;
       }

        DB::table('periodical_likes')->insert($params);
    }

    /**
     * 校验参数
     * @throws CustomException
     */
    private function _validateParamForLike()
    {
        (!request()->post('type') || !in_array(request()->post('type'), ['periodical'])) && $this->_errorShow('type类型异常');
        !request()->post('id') && $this->_errorShow('请传入id');
        if (!in_array(request()->post('like'), [true, false])) {
            $this->_errorShow('请输入like');
        }
    }

    /**
     * 异常处理
     * @param string $msg
     * @throws CustomException
     */
    private function _errorShow(string $msg)
    {
        throw new CustomException($msg);
    }

    /**
     * 生成私有密钥
     * @return string
     * @throws CustomException
     */
    public function genPersonalToken(): array
    {
        // 校验参数
        $this->_validateParamsForPersonalToken();

        // 生成私有密钥
        return $this->_genToken();
    }

    /**
     * 生成私有密钥
     * @return array
     */
    private function _genToken(): array
    {
        $wechat_id = request()->post('open_id');
        $access_token = \App\WechatUser::getOneItem(compact('wechat_id'))->createToken('mini program')->accessToken;
        return compact('access_token');
    }

    /**
     * 校验参数
     * @throws CustomException
     */
    private function _validateParamsForPersonalToken()
    {

        list($open_id, $nick_name, $city, $sign, $personal_token) = [
            request()->post('open_id'),
            request()->post('nick_name'),
            request()->post('city'),
            request()->post('sign'),
            $this->slat_personal_token
        ];

        $params = compact('open_id', 'nick_name', 'city', 'sign');

        // 检查属性是否存在
        $this->_validateFieldExist($params);

        // 校验签名是否正确
        $this->_validateSignForToken(compact('open_id', 'nick_name', 'city', 'personal_token'), $sign);

        // 校验是否存在对应账号
        $this->_validateHasWetchatUser($open_id);
    }

    /**
     * 校验是否存在对应账号
     * @param string $wechat_id
     * @throws CustomException
     */
    private function _validateHasWetchatUser(string $wechat_id)
    {
        if (!WechatUser::getOneItem(compact('wechat_id'), ['id'])) {
            throw new CustomException('open_id异常');
        }
    }

    /**
     * 校验签名是否正确
     * @param array $params
     * @param string $sign
     * @throws CustomException
     */
    private function _validateSignForToken(array $params, string $sign)
    {
        ksort($params);
        $params_str = implode(',', $params);
        if (md5(md5($params_str)) != $sign) {
            throw new CustomException('签名不合法');
        }
    }

    /**
     * 校验字段是否存在
     * @throws CustomException
     * @param array $params
     */
    private function _validateFieldExist(array $params)
    {
        array_walk($params, function ($item, $key) {
            if (!$item) {
                throw new CustomException('请输入' . $key . '参数');
            }
        });
    }

    /**
     * 登陆
     * @throws CustomException
     */
    public function login()
    {
        // 校验参数
        $this->validateParamsForLogin();

        // 登陆
        $this->loginDo();
    }

    /**
     * 登陆
     */
    private function loginDo()
    {
        // 是否已经已经注册过
        if ($this->_determineHasRegister()) {
            return;
        }

        // 注册
        list($wechat_id, $name, $country, $province, $city, $avatar_url) = [
            request()->post('open_id'),
            request()->post('nick_name'),
            request()->post('country'),
            request()->post('province'),
            request()->post('city'),
            request()->post('avatar_url'),
        ];

        WechatUser::create(compact('wechat_id', 'name', 'country', 'province', 'city', 'avatar_url'));
    }

    /**
     * 是否已经已经注册过
     * @return bool
     */
    private function _determineHasRegister(): bool
    {
        $wechat_id = request()->post('open_id');
        return (bool)WechatUser::getOneItem(compact('wechat_id'));
    }


    /**
     * 校验参数
     * @throws CustomException
     */
    private function validateParamsForLogin()
    {
        list($open_id, $nick_name, $country, $province, $city, $avatarUrl) = [
            request()->post('open_id'),
            request()->post('nick_name'),
            request()->post('country'),
            request()->post('province'),
            request()->post('city'),
            request()->post('avatar_url'),
        ];
        $params = compact('open_id', 'nick_name', 'country', 'province', 'city', 'avatarUrl');
        array_walk($params, function ($item, $key) {
            if (!$item) {
                throw new CustomException('请输入' . $key . '参数');
            }
        });
    }

    /**
     * 获取会话密钥
     * @return array
     * @throws CustomException
     */
    public function codeToSession(): array
    {
        // 校验条件
        $this->_validateParamsForSession();

        // 生成条件
        $params = $this->_genParamsForSession();

        // 获取session
        return $this->_getSession($params);
    }

    private function _genParamsForSession(): array
    {
        list($js_code, $appid, $secret, $grant_type) = [
            request()->get('js_code'), // 会话code
            request()->get('appid'),
            request()->get('secret'),
            request()->get('grant_type'), // 申请得权限类型
        ];
        return compact('js_code', 'appid', 'secret', 'grant_type');
    }

    /**
     * @param array $params
     * @return array
     */
    private function _getSession(array $params): array
    {
        $url = env('MINI_CODE_TO_SESSION');
        return $this->get($url, $params);
    }

    /**
     * 校验条件
     * @throws CustomException
     */
    private function _validateParamsForSession()
    {
        list($js_code, $appid, $secret, $grant_type) = [
            request()->get('js_code'), // 会话code
            request()->get('appid'),
            request()->get('secret'),
            request()->get('grant_type'), // 申请得权限类型
        ];

        if (!$js_code) {
            throw new CustomException('请输入js_code');
        }

        if (!$appid) {
            throw new CustomException('请输入appid');
        }

        if (!$secret) {
            throw new CustomException('请输入secret');
        }

        if (!$grant_type) {
            throw new CustomException('请输入grant_type');
        }
    }

    /**
     * 解密用户信息
     * @return array
     * @throws CustomException
     */
    public function decode(): array
    {
        // 校验参数'
        $this->_validateParamForDecode();

        // 解密
        return $this->_decode();
    }

    private function _decode(): array
    {
        // 参数
        list($appid, $sessionKey, $encryptedData, $iv) = [
            request()->post('appid'),
            request()->post('sessionKey'),
            request()->post('encryptedData'),
            request()->post('iv'),
        ];

        // 解密
        $pc = new WXBizDataCrypt($appid, $sessionKey);
        $errCode = $pc->decryptData($encryptedData, $iv, $data);

        if ($errCode == 0) {
            return json_decode($data, true);
        }
        return compact('errCode');
    }

    /**
     * 校验参数
     * @throws CustomException
     */
    public function _validateParamForDecode()
    {
        list($appid, $sessionKey, $encryptedData, $iv) = [
            request()->post('appid'),
            request()->post('sessionKey'),
            request()->post('encryptedData'),
            request()->post('iv'),
        ];

        // appid 是否存在
        if (!$appid) {
            throw new CustomException('请输入appid');
        }

        // sessionKey 是否存在
        if (!$sessionKey) {
            throw new CustomException('请输入sessionKey');
        }

        // 加密信息
        if (!$encryptedData) {
            throw new CustomException('请输入encryptedData');
        }

        if (!$iv) {
            throw new CustomException('请输入iv');
        }
    }
}