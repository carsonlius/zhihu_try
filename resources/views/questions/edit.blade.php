@extends('layouts.app')

@section('content')
    <div class="container">
        @include('vendor.ueditor.assets')
        <style>
            .panel-body img {
                width: 100%;
            }
        </style>
        <div class="row">
                <div class="panel panel-default">
                    <div class="col-md-8 col-md-offset-2">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div class="panel-heading">修改问题</div>

                    {!! Form::model($question, ['url'=> '/Question/update/' . $question->id, 'method' => 'put']) !!}
                    {!! Form::hidden('id', $question->id) !!}
                    <div class="panel-body">
                        <div class="form-group {{ $errors->has('title') ? ' has-error' : '' }}">
                            {!! Form::label('title', '标题') !!}
                            {!! Form::text('title', null, ['class' => 'form-control', 'placeholder' => '请填写标题']) !!}
                            @if ($errors->has('title'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('title') }}</strong>
                                    </span>
                            @endif
                        </div>

                        <div class="form-group">
                            {!! Form::label('topic', '话题') !!}
                            <select class="js-example-basic-multiple form-control" id="topic" name="topic[]" multiple="multiple">
                                @foreach ($question->topic as $topic)
                                    <option value="{!! $topic->id !!}" selected>{!! $topic->name !!}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group {{ $errors->has('body') ?  'has-error' : '' }}">
                            <script id="container" name="body"  type="text/plain">{!! $question->body !!} </script>
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
                    {{-- begin --}}
                    <div class="select2-result-repository clearfix">
                        <div class="select2-result-repository_meta">
                            <div class="select2-result-repository_title">
                            </div>
                        </div>
                    </div>
                    {{-- end --}}
                </div>
            </div>
        </div>
    </div>

    <!-- 实例化编辑器 -->
    <script type="text/javascript">

        $(function () {
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

            // topic select2
            var url_topic = '/api/topics';
            $('.js-example-basic-multiple').select2({
                ajax: {
                    url: url_topic,
                    dataType: 'json', // 返回数据的格式
                    cache: true,
                    data: function (params) {
                        return {search: params.term};
                    },
                    delay: 500,
                    processResults: function (data, params) { // select 要求返回格式必须含有 results属性
                        return {
                            results: data
                        };
                    }
                },
                minimumInputLength: 2,
                placeholder: '选择相关话题', // 占位符
                tags: true, // 允许客户键入搜索内容
                templateResult: formatTopic, // 定制搜索框被加载的时候的伪option的样式
                templateSelection: formatToSelection,//定制在option在被用户选中的时候option怎么展示，
                formatSearching: '搜索中...，请耐心等待',
                allowClear: true,//允许清空
                escapeMarkup: function (markup) {
                    return markup;
                }, // 自定义格式化防止xss注入
                language: "zh-CN"//汉化
            });
        });

        // topic展示元素的定制
        function formatTopic(topic) {
            return topic.name;
        }

        function formatToSelection(topic) {
            // 如果又返回的话 则选择放回没有的话 选择用户自己输入的
            return topic.name || topic.text;
        }
    </script>
@endsection
