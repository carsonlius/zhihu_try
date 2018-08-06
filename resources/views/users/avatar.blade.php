@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">更换头像</div>
                    <div class="panel-body">
                        <avatar-user avatar_user="{{ user()->avatar }}" csrf_token="{{ csrf_token() }}"></avatar-user>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
