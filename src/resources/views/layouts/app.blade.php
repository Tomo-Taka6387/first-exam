<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CT COACHTECH</title>
    <link rel="stylesheet" href="https://unpkg.com/ress/dist/ress.min.css" />
    <link rel="stylesheet" href="{{ asset('css/app.css')}}?v={{ time() }}">
    @yield('css')
</head>

<body>
    <div class="app">
        <header class="header">
            <div class="header_img">
                <img src="{{ asset('icons/logo.svg') }}" alt="ロゴ画像">
            </div>
            <div class="header_buttons">
                <form class="search-form" method="GET" action="{{ route('index') }}">
                    <input class="search-form_input" type="text" name="keyword" value="{{ $keyword ?? '' }}" placeholder="なにをお探しですか？">
                </form>

                <nav class="header_links">
                    @auth
                    <a class="logout_btn" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">ログアウト</a>
                    @else
                    <a href="{{ route('login') }}" class="login-btn">ログイン</a>
                    @endauth

                    <a class="mypage_btn" href="{{ route('mypage') }}">マイページ</a>
                    <button class="sell_btn" onclick="location.href='{{ route('items.create') }}'">出品</button>
                </nav>

                @auth
                <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display: none;">
                    @csrf
                </form>
                @endauth
            </div>
            @yield('link')
        </header>
        <div class="content">
            @yield('content')
        </div>
    </div>
</body>

</html>