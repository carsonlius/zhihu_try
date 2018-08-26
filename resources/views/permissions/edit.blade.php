@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <permission-edit permission="{{ $permission }}"></permission-edit>
        </div>
    </div>
@endsection