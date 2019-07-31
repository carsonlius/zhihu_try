@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <periodical-edit periodical='{!! $periodical !!}'></periodical-edit>
        </div>
    </div>
@endsection