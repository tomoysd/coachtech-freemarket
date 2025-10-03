<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'COACHTECH フリマ')</title>

    <!-- リセットCSS（お好みのものに差し替えOK） -->
    <link rel="preconnect" href="https://fonts.gstatic.com" />
    <link rel="stylesheet" href="https://unpkg.com/modern-css-reset/dist/reset.min.css" />
    <link rel="stylesheet" href="{{ asset('css/common.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/items.css') }}">

    @stack('head')
</head>

<body class="bg-body">

    <!-- ====== Header ====== -->
    <header class="site-header">
        <div class="wrap header-inner">

            <!-- ロゴ -->
            <a href="{{ url('/') }}" class="logo" aria-label="COACHTECH top">
                <img src="{{ asset('images/logo.svg') }}" alt="COACHTECH" />
            </a>

            <!-- 検索フォーム -->
            <form action="{{ route('items.index') }}" method="get" class="search">
                <input
                    type="text"
                    name="q"
                    value="{{ request('q') }}"
                    placeholder="なにをお探しですか？"
                    class="search-input" />
                <button class="search-btn" type="submit">検索</button>
            </form>

            <!-- 右上ナビ -->
            <nav class="nav">
                @auth
                <a href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    ログアウト
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="post" style="display:none;">
                    @csrf
                </form>

                <a href="{{ route('mypage') }}">マイページ</a>
                <a href="{{ route('items.create') }}" class="btn-primary">出品</a>
                @else
                <a href="{{ route('login') }}">ログイン</a>
                <a href="{{ route('mypage') }}">マイページ</a>
                <a href="{{ route('items.create') }}" class="btn-primary">出品</a>
                @endauth
            </nav>

        </div>

        <!-- タブ（トップ画面などで使う） -->
        @hasSection('tabs')
        <div class="tabs-bar">
            <div class="wrap tabs">
                @yield('tabs')
            </div>
        </div>
        @endif
    </header>

    <!-- フラッシュメッセージ -->
    @if (session('message'))
    <div class="flash wrap">{{ session('message') }}</div>
    @endif

    <!-- ====== Main ====== -->
    <main class="wrap main">
        @yield('content')
    </main>

    <!-- ====== Footer ====== -->
    <footer class="site-footer">
        <div class="wrap">
            © {{ date('Y') }} COACHTECH
        </div>
    </footer>

    @stack('scripts')
</body>

</html>