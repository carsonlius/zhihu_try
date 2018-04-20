@extends('layouts.app')

@section('content')
    <div class="container">
        @include('vendor.ueditor.assets')
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div class="panel-heading">
                            {{ $question->title }}
                            @foreach ($question->topic as $topic)
                            <a href="#"><span class="topic">{{ $topic['name'] }}</span></a>
                            @endforeach
                    </div>
                    <div class="panel-body">
                        {!! $question->body !!}
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
