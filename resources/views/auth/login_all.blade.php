@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('ログイン') }}</div>

                <div class="card-body">
                    <div class="mb-3">
                        <a href="{{ route('login') }}">メールアドレスでログインする</a>
                    </div>
                    
                    <div class="mb-3">
                        <a href="{{ url('/auth/github') }}">Githubアカウントでログインする</a>
                    </div>

                    <div class="mb-3">
                        <a href="{{ url('/auth/google') }}">Googleアカウントでログインする</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
