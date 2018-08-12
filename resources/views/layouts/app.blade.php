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
    {{--<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">--}}
    <script src="https://cdn.bootcss.com/jquery/1.12.4/jquery.min.js"></script>

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css?version=1.86')}}" rel="stylesheet">

    {{-- 最完美的bootstrap图标 --}}
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">

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
                    &nbsp;
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
                            <info_user login_name="{{ user()->name }}"></info_user>
                        </li>
                        {{--<li class="dropdown">--}}
                            {{--<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"--}}
                               {{--aria-expanded="false" aria-haspopup="true" v-pre>--}}
                                {{--{{ Auth::user()->name }} <span class="caret"></span>--}}
                            {{--</a>--}}

                            {{--<ul class="dropdown-menu">--}}
                                {{--<li>--}}
                                    {{--<a href="{{ route('logout') }}"--}}
                                       {{--onclick="event.preventDefault();--}}
                                                     {{--document.getElementById('logout-form').submit();">--}}
                                        {{--退出--}}
                                    {{--</a>--}}

                                    {{--<form id="logout-form" action="{{ route('logout') }}" method="POST"--}}
                                          {{--style="display: none;">--}}
                                        {{--{{ csrf_field() }}--}}
                                    {{--</form>--}}
                                {{--</li>--}}
                            {{--</ul>--}}
                        {{--</li>--}}
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
