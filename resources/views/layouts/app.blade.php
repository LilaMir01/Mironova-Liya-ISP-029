<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Альта Дизайн — Фасадные материалы')</title>
    <link rel="icon" href=" {{asset('images/favicon-32x32.png')}}" type="image/x-icon">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <header class="site-header">
        <div class="header-container">
            <a href="{{ url('/') }}" class="header-logo">
                <img src="{{ asset('images/logo.jpg') }}" alt="Лого" class="logo-img" onerror="this.style.display='none'; this.nextElementSibling.style.display='inline';">
                <span class="logo-placeholder" style="display:none;">Лого</span>
            </a>
            <nav class="header-nav">
                <a href="{{ route('products.index') }}" class="nav-link">Товары</a>
                <a href="{{ route('stores.index') }}" class="nav-link">Торговые точки</a>
                @if(auth()->user()?->isAdmin())
                <a href="{{ route('analytics.index') }}" class="nav-link">Аналитика</a>
                @endif
            </nav>
            @auth
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn-login btn-logout">Выход</button>
                </form>
            @else
                <div class="header-auth-buttons">
                    <a href="{{ route('login') }}" class="btn-login">Войти</a>
                    <a href="{{ route('register') }}" class="btn-login btn-login-register">Регистрация</a>
                </div>
            @endauth
        </div>
    </header>

    <main class="main-content">
        @yield('content')
    </main>
</body>
</html>
