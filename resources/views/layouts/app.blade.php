<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    {{-- FontAwesome --}}
    <link rel ="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <!-- ↓↓↓Firebase↓↓↓ -->
    <script src="https://www.gstatic.com/firebasejs/ui/6.0.1/firebase-ui-auth.js"></script>
    <link type="text/css" rel="stylesheet" href="https://www.gstatic.com/firebasejs/ui/6.0.1/firebase-ui-auth.css" />
    <!-- ↑↑↑Firebase↑↑↑ -->

</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login_all') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">

                                    {{-- SNS認証を追加するために編集 --}}
                                    <a class="nav-link" href="{{ route('register_all') }}">{{ __('Register') }}</a> 

                                    {{-- 以下はオリジナル --}}
                                    {{-- <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a> --}}
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    {{-- ↓↓↓プロフィール変更ページ作成のため追加↓↓↓ --}}
                                    <a class="dropdown-item" href="{{ url('/user/index') }}">
                                        {{ __('Profile') }}
                                    </a>
                                    {{-- ↑↑↑プロフィール変更ページ作成のため追加↑↑↑ --}}

                                    {{-- 以下はなんだ？？？ --}}
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            <div class="container">
                {{-- マイ物件削除後のメッセージ --}}
                <div>
                    @if(session("success"))
                        <div class="alert alert-success" role="alert">
                            {{ session("success") }}
                        </div>
                    @endif
                </div>
                {{-- マイ物件削除後のメッセージ --}}
                
                @yield('content')
            </div>
        </main>

        @include("common.footer")
    </div>

    {{-- ↓↓↓Firebase↓↓↓ --}}
    {{-- <script src="../../js/firebase.js"></script> --}}
    {{-- <script type="text/javascript" src="{{ asset('/js/firebase.js') }}"></script> --}}
    {{-- ↑↑↑Firebase↑↑↑ --}}
</body>
</html>
