@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <p>{{ __('You are loged in!') }}</p>
                    <p>___id:{{ Auth::id() }}</p>
                    <p>___name:{{ Auth::user()->name }}</p>

                </div>

                <div>ログイン時のみの登録フォーム作成（マイ物件DBをイメージ）</div>
                <form method="POST" action="{{ url('/home') }}">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ $user["id"] }}">

                    <div class="form-group">
                        <textarea name="comment"></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-lg">保存</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
