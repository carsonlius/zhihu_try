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
                        <div class="pull-right">
                            @foreach ($question->topic as $topic)
                                <a href="#"><span class="topic">{{ $topic['name'] }}</span></a>
                            @endforeach
                        </div>

                    </div>
                    <div class="panel-body">
                        {!! $question->body !!}
                    </div>

                    <div class="panel-footer">

                        @if (\Auth::check() && $question->user_id == \Auth::id())

                            <ul class="list-inline">
                                <li>
                                    <div class="actions">
                                        <span><a href="/Question/edit/{{ $question->id }}" class="btn btn-info btn-xs">编辑</a></span>
                                    </div>
                                </li>
                                <li>
                                    <div class="delete-form">
                                        {{ Form::open(['url' => ('/Question/' . $question->id), 'method' => 'DELETE', 'class' => 'delete-form']) }}
                                        {{ Form::submit('删除', ['class' => 'btn btn-info btn-xs']) }}
                                        {{ Form::close() }}
                                    </div>
                                </li>
                            </ul>


                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
