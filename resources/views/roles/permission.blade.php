@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <role-permission role_name="{{ $role_name }}" role_id="{{ $role_id }}"></role-permission>
        </div>
    </div>
@endsection