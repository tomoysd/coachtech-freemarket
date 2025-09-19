<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'COACHTECH フリマ')</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://unpkg.com/modern-css-reset/dist/reset.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @stack('head')
</head>

<body class="bg-body">
    <header class="site-header">
        <div class="wrap header-inner">
            <a href="{{ url('/') }}" class="logo">COACHTECH</a>

            <form action="{{ route('items.index') }}" method="get" class="search">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="なにをお探しですか？" class="search-input">
                <button class="search-btn">検索</button>
            </form>

            <nav class="nav">
                @auth
                <a href="{{ route('logout') }}"
                    onclick="event.preventDefault();document.getElementById('logout-form').submit();">ログアウト</a>
                <form id="logout-form" action="{{ route('logout') }}" method="post" style="display:none;">@csrf</form>
                <a href="{{ route('mypage') }}">マイページ</a>
                <a href="{{ route('items.create') }}" class="btn-primary">出品</a>
                @else
                <a href="{{ route('login') }}">ログイン</a>
                <a href="{{ route('mypage') }}">マイページ</a>
                <a href="{{ route('items.create') }}" class="btn-primary">出品</a>
                @endauth
            </nav>
        </div>
    </header>

    {{-- サブタブ（トップ系の画面で使う） --}}
    @hasSection('tabs')
    <div class="tabs-bar">
        <div class="wrap tabs">
            @yield('tabs')
        </div>
    </div>
    @endif

    {{-- フラッシュメッセージ --}}
    @if (session('message'))
    <div class="flash wrap">{{ session('message') }}</div>
    @endif

    <main class="wrap main">
        @yield('content')
    </main>

    <footer class="site-footer">
        <div class="wrap">© {{ date('Y') }} COACHTECH</div>
    </footer>
    @stack('scripts')
</body>

</html>