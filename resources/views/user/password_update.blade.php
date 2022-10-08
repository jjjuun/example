@extends('layouts.app')

@section('content')

@include("common.content-header")

@if(session('status'))
    <div class="alert alert-danger">
        {{ session('status') }}
    </div>
@endif


@endsection