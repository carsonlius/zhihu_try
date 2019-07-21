<?php


namespace App\Http\Repositories;


use App\Http\TraitHelper\{
    CurlTrait, CustomException
};
use App\MiniUtil\WXBizDataCrypt;

class MiniProgramRepository
{
    use CurlTrait;

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

    private function _decode() : array
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