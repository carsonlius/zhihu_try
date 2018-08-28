@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <role-permission role_name="{{ $role_name }}" role_id="{{ $role_id }}" permission_attached="{{ $permission_attached }}"></role-permission>
        </div>
    </div>
@endsection