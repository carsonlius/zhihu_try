@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="panel panel-default">
                <div class="panel-heading">
                      私信列表
                </div>
                <div class="panel-body">
                    @foreach($list_message as $message)
                        <div class="media">
                            <div class="media-left">
                                <img src="{{ $message->fromUser->avatar }}" alt="" width="60" height="60">
                            </div>
                            <div class="media-body">
                                <h4 class="media-heading">{{ $message->fromUser->name }}</h4>
                                <p>{{ $message->body }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>
@endsection