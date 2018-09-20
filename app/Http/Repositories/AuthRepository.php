<?php

namespace App\Http\Repositories;

use App\User;
use Overtrue\LaravelSocialite\Socialite;

class AuthRepository
{
    /*
     * 第三方的返回的user object
     * */
    protected $user;

    /*
     * 支持的第三方验证
     * */
    protected $list_legal_oauth = [
        'github' => 'GITHUB',
    ];

    /**
     * github的回调函数
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function gitHubCallback()
    {
        $this->user = Socialite::driver('github')->user();

        // 检查登陆方式是否已经注册
        $verify_status = $this->verifyRegisterForGithub();

        // 处理返回信息
        $this->dealProviderData($verify_status);

        // 登陆
        return $this->loginForThird();
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    protected function loginForThird()
    {
        // 登陆
        $this->loginDo();

        // 跳转到来源页
        return $this->loginRedirect();
    }

    /**
     * 登陆后跳转
     */
    protected function loginRedirect()
    {
        $redirect_url = request()->get('redirect_url');
        $redirect_url = $redirect_url ? $redirect_url : '';
        return redirect($redirect_url);
    }

    /**
     * 登陆
     */
    protected function loginDo()
    {
        $email = $this->user->getEmail();
        $user = User::where(compact('email'))->first();
        \Auth::login($user);
    }

    /**
     * 处理第三方登陆的信息
     * @param string $verify_status
     * @throws \Exception
     */
    protected function dealProviderData($verify_status)
    {
        switch ($verify_status) {
            case 'register_not':
                // 如果邮箱没有被注册
                $this->registerUserThenLogin();
                break;
            case 'register_only':
                // 如果邮箱已经被注册，且没有绑定第三方账号
                $this->bindUserFromThird();
                break;
            case 'register_not_any_more':
                // 如果邮箱已经被注册且已经绑定了第三方账号， 且第三方账号不是当前登陆的方式
                $this->bindWhenSomeThingWrong();
                break;
            case 'register_then_login':
                // 如果邮箱已经被注册, 且已经绑定第三方账户， 且登陆方式就是当前的登陆方式 则直接登陆
                break;

        }
    }

    /**
     * 如果邮箱已经被注册且已经绑定了第三方账号， 且第三方账号不是当前登陆的方式
     * @throws \Exception
     */
    protected function bindWhenSomeThingWrong()
    {
        throw new \Exception('该账号已经被绑定');
    }

    /**
     * 第三方账户绑定已经存在的用户
     */
    protected function bindUserFromThird()
    {
        // 参数
        $params = $this->genParamsForBind();

        // 绑定
        $this->bindDo($params);
    }

    /**
     * 绑定
     * @param array $params 需要更新的参数
     */
    protected function bindDo($params)
    {
        $email = $this->user->getEmail();
        User::where(compact('email'))->update($params);
        flash('谢谢来访, 您的github账号已经绑定到您之前注册Email: ' . $email . ', 如果有疑问请及时联系')->success();
    }

    /**
     * 为绑定已经存在的账号生成参数
     * @return array
     */
    protected function genParamsForBind()
    {
        $provider_name = strtolower(trim($this->user->getProviderName()));
        $provider_id = $this->user->getId();
        return compact('provider_id', 'provider_name');
    }


    /**
     * 如果邮箱没有被注册，则注册 然后登陆
     * @param object $user
     */
    protected function registerUserThenLogin()
    {
        // 参数
        $params = $this->genParamsForRegister();

        // create
        User::create($params);
    }

    /**
     * 为注册用户生成参数
     * @return array
     */
    protected function genParamsForRegister()
    {
        $provider_id = $this->user->getId();
        $name = $this->user->getName();
        $email= $this->user->getEmail();
        $provider_name= strtolower($this->user->getProviderName());
        $avatar = $this->user->getAvatar(); // GitHub
        $confirmation_token = sha1(str_random(40));
        $api_token = str_random(64);
        $settings = ['area' => '', 'bio' => ''];
        return compact('provider_name', 'provider_id', 'name', 'email', 'avatar', 'confirmation_token', 'api_token', 'settings');
    }

    /**
     * 检查用户是否已经注册
     * @return boolean
     */
    protected function verifyRegisterForGithub()
    {
        $email= $this->user->getEmail();
        $provider_name= strtolower(trim($this->user->getProviderName()));

        $user_search = User::where(compact('email'))->first();
        // 如果邮箱没有被注册
        if (!$user_search) {
            return 'register_not';
        }

        // 如果邮箱已经被注册, 且没有绑定第三方账户 则做关联然后登陆
        if (!$user_search->provider_name) {
            return 'register_only';
        }

        // 如果邮箱已经被注册, 且已经绑定第三方账户， 且登陆方式不是当前的登陆方式 则提示邮箱已经被注册
        if ($user_search->provider_name !== $provider_name) {
            return 'register_not_any_more';
        }

        // 如果邮箱已经被注册, 且已经绑定第三方账户， 且登陆方式就是当前的登陆方式 则直接登陆
        return 'register_then_login';
    }


    /**
     * 跳转验证
     * @throws \Exception
     */
    public function redirectToProvider()
    {
        // 检查参数
        $this->verifyParams();

        // 跳转
        return $this->redirectOauth();
    }

    /**
     *  跳转
     */
    protected function redirectOauth()
    {
        $oauth_login = request('oauth_login');
        switch ($oauth_login) {
            case 'github':
                return Socialite::driver('github')->redirect();
                break;
            default:
        }
    }

    /**
     * 校验参数
     * @throws \Exception
     */
    protected function verifyParams()
    {
        $oauth_login = request('oauth_login');
        if (!$oauth_login) {
            throw new \Exception('网络故障,请刷新后再试');
        }

        if (!array_key_exists($oauth_login, $this->list_legal_oauth)) {
            $list_legal = implode(',', $this->list_legal_oauth);
            throw new \Exception('抱歉，目前仅支持' . $list_legal . '的第三方登陆');
        }
    }

}