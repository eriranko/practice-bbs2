<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Document</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    @yield('css')
</head>

<body>
    <header class="header">
        <div class="header__inner">
            <div class="header__utilities">
                <a class="header__logo" href="/">
                    {{ config('app.name', '掲示板') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
            <div class="header-nav">
                @if (Auth::check())
                <div class="welcome">
                    <span class="header-nav__greeting">ようこそ、{{ Auth::user()->nickname }}さん</span>
                </div>
                <ul class="header-nav__inner">
                    <li class="header-nav__item">
                        <a class="header-nav__list" href="/create">投稿する</a>
                    </li>
                    <li class="header-nav__item">
                        <a class="header-nav__list" href="{{ route('mypage') }}">マイページ</a>
                    </li>
                    <li class="header-nav__item">
                        <a class="header-nav__list" href="/logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">ログアウト</a>
                        <form id="logout-form" action="/logout" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
                </ul>
                @else
                <ul class="header-nav__inner">
                <li class="header-nav__item">
                    <a class="header-nav__list" href="/register">ユーザー登録</a>
                </li>
                <li class="header-nav__item">
                    <a class="header-nav__list" href="/login">ログイン</a>
                </li></ul>
                @endif
            </div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    @yield('script')

</body>
</html>