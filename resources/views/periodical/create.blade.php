@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <periodical-create csrf_token="{{ csrf_token() }}"></periodical-create>
        </div>
    </div>
@endsection