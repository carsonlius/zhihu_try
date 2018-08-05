@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">更换头像</div>

                    <div class="panel-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif
                        <avatar-user avatar_user="{{ user()->avatar }}"></avatar-user>

                        欢迎来到我们的网站!
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
