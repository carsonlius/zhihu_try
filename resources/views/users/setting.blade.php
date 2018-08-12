@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-sm-8 col-sm-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    设置个人信息
                </div>
                <div class="panel-body">
                    <setting-user settings="{{ json_encode(user()->settings) }}"></setting-user>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection