@extends('layouts.app')

@section('content')

@include("common.content-header")

@if(session('warning'))
    <div class="alert alert-danger">
        {{ session('warning') }}
    </div>
@endif

@if(session('success_password'))
    <div class="alert alert-success">
        {{ session('success_password') }}
    </div>
@endif

<form method="POST" action="{{ route("password.update", ["id" => $edit_password["id"]]) }}">
    @csrf

    <div class="card mb-3">
        <div class="card-header" style="display: flex; justify-content: space-between;">
            <p class="d-flex align-items-center" style="margin: 0;">パスワード編集</p>
            <button type="button" class="btn btn-primary" style="margin: 0;"><a style="text-decoration: none;" class="text-white" href="/user/index">プロフィールに戻る</a></button>
        </div>

        <div class="card-body">
            <label>旧パスワード</label>
            <div>
                <input type="text" name="current_password" value="">
            </div>
        </div>

        <div class="card-body">
            <label>新しいパスワード</label>
            <div>
                <input type="text" name="new_password" placeholder="英数字8文字以上" value="">
            </div>
        </div>

        <div class="card-body">
            <label>確認用の新しいパスワード</label>
            <div>
                <input type="text" name="new_password_confirm" placeholder="英数字8文字以上" value="">
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary btn-lg">変更する</button>
</form>


@endsection