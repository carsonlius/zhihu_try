@extends('layouts.app')
@section('content')
    <user-role-edit user_id="{{ $user_id }}" user_name="{{ $user_name }}"></user-role-edit>
@endsection