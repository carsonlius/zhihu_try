@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <inbox-list friend_id="{{ $friend_id }}" login_name="{{ $login_name }}"></inbox-list>
        </div>
    </div>
@endsection