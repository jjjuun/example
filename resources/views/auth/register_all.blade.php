@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('ユーザー登録') }}</div>

                <div class="card-body">
                    <div class="mb-3">
                        <a href="{{ route('register') }}">メールアドレスで登録する</a>
                    </div>
                    
                    <div class="mb-3">
                        <a href="{{ url('/auth/github') }}">Githubアカウントで登録する</a>
                    </div>

                    <div class="mb-3">
                        <a href="{{ url('/auth/google') }}">Googleアカウントで登録する</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
