@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        私信列表
                    </div>
                    <div class="panel-body">
                        @foreach($messages as $messageGroup)
                            <div class="media">
                                <div class="media-left">
                                    <a href="#">
                                        <img src="{{ $messageGroup->friendUser->avatar }}" alt="私信来源" height="60px" width="60px">
                                    </a>
                                </div>
                                <div class="media-body {{ $messageGroup->unread_class ? 'unread_bg' : '' }}">
                                    <h4 class="media-heading"><a href="#">{{ $messageGroup->friendUser->name }}</a></h4>
                                    <p>
                                        <a href="/message/{{ $messageGroup->friend_id }}">{{ $messageGroup->body }}</a>
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
