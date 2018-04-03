@extends('layouts.app')

@section('content')
    <div class="container">
        @include('vendor.ueditor.assets')
        <style>
            .panel-body img{
                width: 100%;
            }
        </style>
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div class="panel-heading">发布问题</div>
                    {!! Form::open(['url'=> '/Question/store', 'method' => 'post']) !!}

                    <div class="panel-body">
                        <div class="form-group {{ $errors->has('title') ? ' has-error' : '' }}">
                            {!! Form::label('title', '标题') !!}
                            {!! Form::text('title', old('title'), ['class' => 'form-control', 'placeholder' => '请填写标题']) !!}
                            @if ($errors->has('title'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('title') }}</strong>
                                    </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('body') ?  'has-error' : '' }}">
                            <script style="height: 300px" id="container" name="body" type="text/plain"> {{ old('body') }} </script>
                            @if($errors->has('body'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('body') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group">
                            {!! Form::submit('发布', ['class' => 'btn btn-sm btn-success pull-right']) !!}
                        </div>
                        {!! Form::close() !!}
                    </div>

                </div>
            </div>
        </div>
        {{-- 采用更好看的写法--}}
        {{--<div class="row">--}}
            {{--<div class="col-md-8 col-md-offset-2">--}}
                {{--@if($errors->any())--}}
                    {{--<ol class="alert alert-danger">--}}
                        {{--@foreach($errors->all() as $error)--}}
                            {{--<li>{{ $error }}</li>--}}
                        {{--@endforeach--}}
                    {{--</ol>--}}
                {{--@endif--}}
            {{--</div>--}}
        {{--</div>--}}
    </div>


    <!-- 实例化编辑器 -->
    <script type="text/javascript">
        // var ue = UE.getEditor('container');
        var ue = UE.getEditor('container', {
            toolbars: [
                ['bold', 'italic', 'underline', 'strikethrough', 'blockquote', 'insertunorderedlist', 'insertorderedlist', 'justifyleft','justifycenter', 'justifyright',  'link', 'insertimage', 'fullscreen']
            ],
            elementPathEnabled: false,
            enableContextMenu: false,
            autoClearEmptyNode:true,
            wordCount:false,
            imagePopup:false,
            autotypeset:{ indent: true,imageBlockLine: 'center' }
        });
        ue.ready(function () {
            ue.execCommand('serverparam', '_token', '{{ csrf_token() }}'); // 设置 CSRF token.
        });
    </script>
@endsection
