@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="panel-default panel">
                <div class="panel-heading">
                    <span style="font-weight: bold;">角色列表</span>
                    <span class="pull-right"><a href="/Role/create" class="btn btn-primary btn-xs">新建角色</a></span>
                </div>
            </div>
            <role-list></role-list>

        </div>
    </div>
@endsection