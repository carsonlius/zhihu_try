<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">
    <meta name="api-token" id="api-token" content="{{ \Auth::check() ? 'Bearer ' . \Auth::user()->api_token : 'Bearer '  }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    {{-- select2 需要用到jQ 所以位置需要放到上面 --}}

    {{-- CDN  bootstrap --}}
    <script src="https://cdn.bootcss.com/jquery/1.12.4/jquery.min.js"></script>

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css?version=1.89')}}" rel="stylesheet">

    {{-- 最完美的bootstrap图标 --}}
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">

    {{-- social-share --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/social-share.js/1.0.16/css/share.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/social-share.js/1.0.16/js/social-share.min.js"></script>

    {{-- 网站图标 --}}
    <link rel="shortcut icon" href="{{ asset('images/favicons/favicon.ico') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/favicons/favicon.png') }}" sizes="192x192">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/favicons/favicon.png') }}">
    <script>
        window.Laravel = {!! json_encode([
        'user_id' => auth()->check() ? auth()->user()->id : null,
        'api_token' => auth()->check() ? 'Bearer ' . auth()->user()->api_token : 'Bearer '
        ]) !!};


    </script>
</head>
<body>
<div id="app">
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                        data-target="#app-navbar-collapse" aria-expanded="false">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    @include(config('laravel-menu.views.bootstrap-items'), ['items' => Menu::get('NavPermission')->roots()])
                </ul>


            <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    @guest
                        <li><a href="{{ route('login') }}">登录</a></li>
                        <li><a href="{{ route('register') }}">注册</a></li>
                    @else
                        <li>
                            <bell-notification></bell-notification>
                        </li>
                        <li>
                            <bell-message></bell-message>
                        </li>
                        <li>
                            {{-- 用户信息界面 --}}
                            {{--<info_user login_name="{{ user()->name }}"></info_user>--}}
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                               aria-expanded="false" aria-haspopup="true" v-pre>
                                <img src="{{ user()->avatar}}" style="border-radius: 15px; height: 30px;width: 30px"> {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu">
                                <li><a href="/avatar"><i class="icon-user"></i> 更换头像</a></li>
                                <li>
                                    <a href="/setting" ><i class="icon-cogs"></i> 用户设置</a>
                                </li>
                                <li>
                                    <a href="/password"><i class="icon-pencil"></i> 重置密码</a>
                                </li>
                                <li>
                                    <a href="{{ route('logout') }}" onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();"> <i class="icon-ban-circle"></i> 退出</a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                          style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    {{-- flash --}}
    <div class="container" id="flash_div">
        @include('flash::message')
    </div>
    @yield('js')

    @yield('content')
</div>

<!-- Scripts -->

<script src="{{ mix('/js/app.js') }}"></script>
{{-- CDN select2 --}}
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

<script>
    $('#flash-overlay-modal').modal();
    $('div.alert').not('.alert-important').delay(3000).fadeOut();
</script>
</body>
</html>
