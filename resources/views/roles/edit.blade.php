@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <role-edit role="{{ $role }}"></role-edit>
        </div>
    </div>
@endsection