@extends('layouts.app')

@section('content')

@include("common.content-header")

<form method="POST" action="{{ route("user.update", ["id" => $edit_user["id"]]) }}" enctype="multipart/form-data">
    @csrf

    <div class="card mb-3">
        <div class="card-header" style="display: flex; justify-content: space-between;">
            <p class="d-flex align-items-center" style="margin: 0;">プロフィール編集</p>
            <button type="button" class="btn btn-primary" style="margin: 0;"><a style="text-decoration: none;" class="text-white" href="{{ route("password.edit", ["id" => $edit_user["id"]]) }}">パスワードを変更する</a></button>
            <button type="button" class="btn btn-primary" style="margin: 0;"><a style="text-decoration: none;" class="text-white" href="/user/index">プロフィールに戻る</a></button>
        </div>
        
        <div class="card-body">
            <label>ユーザー名</label>
            <div>
                <input type="text" name="name" value="{{ $edit_user->name }}">
            </div>
        </div>

        <div class="card-body">
            <label>アイコン</label>
            @if($edit_user->file_path)
                <div>
                    <img style="width:50px;" src="{{ Storage::url($edit_user['file_path']) }}" />
                </div>
            @endif
            <div>
                <input type="file" name="image" accept="image/png, image/jpeg">
            </div>
        </div>

        <div class="card-body">
            <label>メールアドレス</label>
            <div>
                <input type="email" name="email" value="{{ $edit_user->email }}">
            </div>
        </div>

        <div class="card-body">
            <label>自己紹介</label>
            <div>
                <input type="textarea" name="self_introduct" value="{{ $edit_user->self_introduct }}">
            </div>
        </div>

        <div class="card-body">
            <label>ユーザースタイル</label>
            <div>
                <select name="user_style">
                    @if($edit_user->user_style == "投資家")
                        <option value="">選択してください。</option>
                        <option value="投資家" selected>投資家</option>
                        <option value="その他" >その他</option>
                    @elseif($edit_user->user_style == "その他")
                        <option value="">選択してください。</option>
                        <option value="投資家">投資家</option>
                        <option value="その他" selected >その他</option>
                    @else
                        <option value="" selected>選択してください。</option>
                        <option value="投資家">投資家</option>
                        <option value="その他">その他</option>
                    @endif
                </select>
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary btn-lg">更新</button>
</form>

@endsection