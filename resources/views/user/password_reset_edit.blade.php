@extends('layouts.app')

@section('content')

@include("common.content-header")

<form method="post">
    @csrf

    <input type="hidden" name="_token" value="{{ $token }}">
    <input type="hidden" name="token" value="{{ $token }}">

    <div class="card mb-3">
        <div class="card-header">パスワードをリセットする</div>



        <div class="card-body">
            <label>メールアドレス</label>
            <input type="email" name="email" value="">
        </div>

        <div class="card-body">
            <label>パスワード</label>
            <input type="password" name="password" value="">
        </div>

        <div class="card-body">
            <label>確認用パスワード</label>
            <input type="password" name="password_confirmation" value="">
        </div>


    </div>

    <button type="submit" class="btn btn-primary btn-lg">パスワードを変更する</button>
</form>

@endsection