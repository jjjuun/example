@extends('layouts.app')

@section('content')

@include("common.content-header")


<div class="card">
    @foreach($user_list as $user)
        <div class="card-header" style="display: flex; justify-content: space-between;">
            <p class="d-flex align-items-center" style="margin: 0;">プロフィール</p>
            <button type="button" class="btn btn-primary" style="margin: 0;"><a style="text-decoration: none;" class="text-white" href="/user/password/edit/{{ $user->id }}">パスワードを変更する</a></button>
            <button type="button" class="btn btn-primary" style="margin: 0;"><a style="text-decoration: none;" class="text-white" href="/user/edit/{{ $user->id }}">プロフィール編集</a></button>
        </div>
    @endforeach

    <div class="card-body">
        @foreach($user_list as $user)
            <div class="row">
                <div class="card-body col-4">ユーザー名</div>
                <div class="card-body col-8">{{$user->name}}</div>
            </div>

            <div class="row">
                <div class="card-body col-4">アイコン</div>
                <div class="card-body col-8">
                    @if($user->file_path)
                    <div>
                        <img style="width:50px;" src="{{ Storage::url($user['file_path']) }}" />
                    </div>
                    @endif
                </div>
            </div>

            <div class="row">
                <div class="card-body col-4">メールアドレス</div>
                <div class="card-body col-8">{{$user->email}}</div>
            </div>

            <div class="row">
                <div class="card-body col-4">自己紹介</div>
                <div class="card-body col-8">{{$user->self_introduct}}</div>
            </div>

            <div class="row">
                <div class="card-body col-4">ユーザースタイル</div>
                <div class="card-body col-8">{{$user->user_style}}</div>
            </div>
        @endforeach

    </div><!-- //.card-body -->
</div><!-- //.card -->

@endsection