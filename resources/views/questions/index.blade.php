@extends('layouts.app')
<style>
    img {
        width: 100px;
        height: 120px;
    }
</style>
@section('content')
    <div class="container">
        <div class=row>
            <div class="col-md-8 col-md-offset-2">
                @foreach ($questions as $question)
                    <div class="media">
                        <div class="media-left">
                            <a href=""> <img src="{{ $question->user->avatar }}" alt=""></a>
                        </div>
                        <div class="media-body">
                            <div class="media-heading">
                                <h4><a href="/Question/show/{{ $question->id }}">{{ $question->title }}</a></h4>
                            </div>
                        </div>
                    </div>
                @endforeach
                    {{ $questions->links()  }}
            </div>
        </div>
    </div>
@endsection