@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <inbox-list friend_id="{{ $friend_id }}" friend_name="{{ $friend_name }}"></inbox-list>
        </div>
    </div>
@endsection