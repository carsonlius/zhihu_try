@component('mail::message')
# {{$user->name}}  您好!
### 感谢您对论坛的支持。

    您的论坛帐号为：{{ $user->name }}.
    邮箱验证成功后，您可以使用邮箱作为登录帐号。请点击以下链接进行验证：

@component('mail::button', ['url' => config('app.url') . 'EmailConfirm/Activate/' . $user->confirmation_token])
点击激活
@endcomponent

    如果以上链接无法点击，请将上面的地址复制到您的浏览器(如IE)的地址栏进 http://zhihu.carsonlius.cn/EmailConfirm/Activate/{{ $user->confirmation_token }}
    （该链接在48小时内有效，48小时后需要获取，账号激活后链接失效）。

    如您需要更多帮助，欢迎联系我们：
    系统管理员 :carsonlius@163.com

    © 2018, 北京市朝阳区朝来科技园内

@endcomponent
