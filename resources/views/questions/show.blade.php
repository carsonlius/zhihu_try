
@extends('layouts.app')
@section('js')
    @include('vendor.ueditor.assets')
@endsection
@section('content')
    <style>
        .show_question_page li {
            margin: 0;
            padding: 0;
        }

        .show_question_page ul{
            margin-top: 50px;
        }

        .show_question_page .second_row {
            margin-top: 10px;
        }

        .show_question_page .second_row .media-left img {
            width: 80px;
            height: 80px;
        }
    </style>

    <div class="container show_question_page">

        <div class="row">
            <div class="col-md-8 col-md-offset-1">
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
                        <ul class="list-inline">
                            @if (\Auth::check())
                                @if($question->user_id == \Auth::id())
                                    <li>
                                        <span><a href="/Question/edit/{{ $question->id }}" class="btn btn-info btn-sm">编辑</a></span>
                                    </li>
                                    <li>
                                        {{ Form::open(['url' => ('/Question/' . $question->id), 'method' => 'DELETE']) }}
                                        {{ Form::submit('删除', ['class' => 'btn btn-info btn-sm']) }}
                                        {{ Form::close() }}
                                    </li>
                                @endif
                                <li><span><button class="btn btn-info btn-sm" id="answer_button">写回答</button></span></li>
                            @else
                                <li><span><a href="{{ url('login') }}" class="btn btn-info btn-xs">登录并提交答案</a></span></li>
                            @endif
                            <li>
                                <comment-button count="{{ $question->comments_count }}" type="question" id="{{ $question->id }}"></comment-button>
                            </li>
                        </ul>
                    </div>
                    <div class="panel-footer">
                        分享文章到
                        <div class="share-component" data-disabled="google,twitter,facebook" data-description="Share.js - 一键分享到微博，QQ空间，腾讯微博，人人，豆瓣"></div>
                    </div>
                </div>
            </div>

            {{-- 右侧的状态栏 --}}
            <div class="col-md-3">
                <div class="panel panel-default question_flower">
                    <div class="panel-heading">

                        <span><a id="question_following">{{ $question->flowers_count }}</a>个关注</span>
                        <span><a>{{ $question->answers_count }}</a>个回答</span>
                    </div>
                    <div class="panel-body">
                        <question-follow-button question_id="{{ $question->id }}" id="{{ \Auth::check() ? \Auth::id() : false  }}"></question-follow-button>
                        <a href="#editor" class="btn btn-primary btn-sm">撰写答案</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="panel panel-default">
                    <div class="panel-heading text-center">
                        <h5>关于作者</h5>
                    </div>
                    <div class="panel-body">
                        <div class="media">
                            <div class="media-left">
                                <a href="#"><img width="50px" src="{{ $question->user->avatar }}" alt="{{ $question->user->name }}"></a>
                            </div>
                            <div class="media-body center-align">
                               <p><h4 class="media-heading"><a href=""> {{ $question->user->name }}</a></h4></p>
                            </div>

                            <div class="user-statics">
                                <div class="statics-item text-center">
                                    <div class="statics-text">问题</div>
                                    <div class="statics-count"> {{ $question->user->questions_count }}</div>
                                </div>
                                <div class="statics-item text-center">
                                    <div class="statics-text">回答</div>
                                    <div class="statics-count"> {{ $question->user->answers_count }}</div>
                                </div>
                                <div class="statics-item text-center">
                                    <div class="statics-text"> 关注者</div>
                                    <div class="statics-count" id="following_count"> {{ $question->user->following_count }}</div>
                                </div>
                            </div>
                            <div class="panel-body text-center">
                                    @if(\Auth::check() && \Auth::id() !== $question->user->id)
                                        <ul class="list-inline">
                                            <li><user-follow-button user="{{ $question->user->id }}" id="{{ \Auth::check() ? \Auth::id() : ''}}"></user-follow-button></li>
                                            <li><send-message-button user_id="{{ $question->user->id }}"></send-message-button></li>
                                        </ul>
                                    @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 回答 --}}
        <div class="row">
            <div class="col-md-8 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        {{ $question->answers_count }}个回答
                    </div>
                    <div class="panel-body" id="answer_body" style="display:{{ $errors->has('body') ? 'block' : 'none' }}">
                        <div class="first_row">
                            {!! Form::open(['url' => '/Answer/', 'method' => 'post']) !!}
                            {!! Form::hidden('question_id', $question->id) !!}
                            <div class="form-group {{ $errors->has('body') ?  'has-error' : '' }}">
                                <script id="container" name="body" type="text/plain"> {!! old('body') !!} </script>
                                @if($errors->has('body'))
                                    <span class="help-block">
                                    <strong>{!! $errors->first('body') !!}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group pull-right">
                                {!! Form::submit('提交答案', ['class' => 'btn-xs btn-primary']) !!}
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>

                    {{-- 循环放出答案 --}}
                    <div class="second_row">
                        <?php $answers = $question->answers()->paginate(3); ?>
                        @foreach ($answers as $answer)
                            <div class="media">
                                <div class="media-left">
                                    <a href=""> <img src="{{ $answer->user->avatar }}" alt="" style="height: 100px;width: 100px"></a>
                                </div>
                                <div class="media-body">
                                    <div class="media-heading">
                                        <h4>{!! $answer->user->name !!}</h4>
                                        {!! $answer->body !!}
                                    </div>
                                    <div>
                                        <ul class="list-inline">
                                            <li><user-vote-button answer_id="{{ $answer->id }}" votes_count="{{ $answer->votes_count }}"></user-vote-button></li>
                                            <li><comment-button count="{{ $answer->comments_count }}" type="answer" id="{{ $answer->id }}"></comment-button></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        {{ $answers->links() }}
                    </div>

                </div>

            </div>
        </div>
    </div>

    <script>
        $(function () {
            // 初始化答案的编辑器
            editorInit();
            // 回答的开关
            answerClick();
        });

        // 动态展示答案的编辑框
        function answerClick() {
            $('#answer_button').click(function () {
                    // 点击之后 在隐藏和显示之间转换
                    var answer_body = $('#answer_body');
                    var css_attr = answer_body.css('display');
                    if (css_attr === 'none') {
                        answer_body.css('display', 'block');
                        $(this).text('取消回答');
                    } else {
                        answer_body.css('display', 'none');
                        $(this).text('写回答');
                    }
                }
            );
        }


        // 初始化答案的编辑器
        function editorInit() {
            // var ue = UE.getEditor('container');
            var ue = UE.getEditor('container', {
                toolbars: [
                    ['bold', 'italic', 'underline', 'strikethrough', 'blockquote', 'insertunorderedlist', 'insertorderedlist', 'justifyleft', 'justifycenter', 'justifyright', 'link', 'insertimage', 'fullscreen']
                ],
                elementPathEnabled: false,
                enableContextMenu: false,
                autoClearEmptyNode: true,
                wordCount: false,
                imagePopup: false,
                autotypeset: {indent: true, imageBlockLine: 'center'}
            });
            ue.ready(function () {
                ue.execCommand('serverparam', '_token', '{{ csrf_token() }}'); // 设置 CSRF token.
            });
        }
    </script>
@endsection
