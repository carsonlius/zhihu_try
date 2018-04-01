@extends('layouts.app')

@section('content')
    <div class="container">
        @include('vendor.ueditor.assets')
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
                        <div class="form-group">
                            {!! Form::label('title', '标题') !!}
                            {!! Form::text('title', null, ['class' => 'form-control', 'placeholder' => '请填写标题']) !!}
                        </div>

                        <div class="form-group">
                            <script id="container" name="body" type="text/plain"></script>
                        </div>

                        <div class="form-group">
                            {!! Form::submit('发布', ['class' => 'btn btn-sm btn-success pull-right']) !!}
                        </div>
                        {!! Form::close() !!}
                    </div>

                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                @if($errors->any())
                    <ol class="alert alert-danger">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ol>
                @endif
            </div>
        </div>
    </div>

    <!-- 实例化编辑器 -->
    <script type="text/javascript">
        var ue = UE.getEditor('container');
        ue.ready(function () {
            ue.execCommand('serverparam', '_token', '{{ csrf_token() }}'); // 设置 CSRF token.
        });
    </script>
@endsection
