{{-- ↓↓↓【20221003】パスワードリセット機能作成のため追加↓↓↓ --}}

@extends('layouts.app')

@section('content')

@include("common.content-header")

<a href="https://readouble.com/laravel/8.x/ja/passwords.html">参考文献</a>



<form method="post">
    @csrf
    <div class="card mb-3">
        <div class="card-header">パスワードリセット用メール送信</div>

        <div class="card-body">
            <input type="email" name="email" value="">
        </div>

    </div>

    <button type="submit" class="btn btn-primary btn-lg">送信する</button>
</form>


@endsection

{{-- ↑↑↑【20221003】パスワードリセット機能作成のため追加↑↑↑ --}}