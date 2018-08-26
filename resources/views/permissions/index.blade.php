@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="panel-default panel">
                <div class="panel-heading">
                    <span style="font-weight: bold;">权限列表</span>
                    <span class="pull-right"><a href="/permission/create" class="btn btn-primary btn-xs">新建权限</a></span>
                </div>
            </div>
            <permission-list></permission-list>
        </div>
    </div>
@endsection