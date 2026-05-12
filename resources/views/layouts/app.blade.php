<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Альта Дизайн — Фасадные материалы')</title>
    <meta name="description" content="@yield('meta_description', 'Альта Дизайн — интернет-магазин фасадных материалов: сайдинг, панели, термопанели и комплектующие. Каталог, консультация и заказ онлайн.')">
    <meta name="keywords" content="@yield('meta_keywords', 'фасадные материалы, сайдинг, фасадные панели, термопанели, купить фасадные материалы, Альта Дизайн')">
    <link rel="icon" href=" {{asset('images/favicon-32x32.png')}}" type="image/x-icon">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <header class="site-header">
        <div class="header-container">
            <a href="{{ route('home') }}" class="header-logo">
                <img src="{{ asset('images/logo.jpg') }}" alt="Лого" class="logo-img" onerror="this.style.display='none'; this.nextElementSibling.style.display='inline';">
                <span class="logo-placeholder" style="display:none;">Лого</span>
            </a>
            <nav class="header-nav header-nav-main" aria-label="Основное меню">
                <a href="{{ url('/#about') }}" class="nav-link">О нас</a>
                <div class="nav-dropdown">
                    <a href="{{ route('catalog.index') }}" class="nav-link">Продукция</a>
                    <div class="mega-menu">
                        @php
                            $__megaFacade = \App\Http\Controllers\CatalogController::facadeSectionNames();
                            $__megaMid = (int) ceil(count($__megaFacade) / 2);
                            $__megaFacadeA = array_slice($__megaFacade, 0, $__megaMid);
                            $__megaFacadeB = array_slice($__megaFacade, $__megaMid);
                        @endphp
                        <div class="mega-menu-grid mega-menu-grid--three">
                            <div class="mega-menu-facade-block">
                                <h4 class="mega-menu-heading mega-menu-heading--facade-span">Фасадные материалы</h4>
                                <div class="mega-menu-facade-cols">
                                    <div class="mega-menu-col">
                                        @foreach($__megaFacadeA as $megaSectionName)
                                            <a href="{{ route('catalog.section', ['category' => 'facade', 'sectionSlug' => \Illuminate\Support\Str::slug($megaSectionName)]) }}">{{ $megaSectionName }}</a>
                                        @endforeach
                                    </div>
                                    <div class="mega-menu-col">
                                        @foreach($__megaFacadeB as $megaSectionName)
                                            <a href="{{ route('catalog.section', ['category' => 'facade', 'sectionSlug' => \Illuminate\Support\Str::slug($megaSectionName)]) }}">{{ $megaSectionName }}</a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="mega-menu-col mega-menu-col--mega-others">
                                <h4 class="mega-menu-heading">Другие категории</h4>
                                <a href="{{ route('catalog.category', 'floor') }}">Напольные покрытия</a>
                                <a href="{{ route('catalog.category', 'terrace') }}">Террасная доска</a>
                                <a href="{{ route('catalog.category', 'drainage') }}">Водосточные системы</a>
                                <a href="{{ route('catalog.category', 'ventilation') }}">Вентиляция</a>
                                <a href="{{ route('catalog.category', 'insulation') }}">Утеплитель и изоляция</a>
                            </div>
                        </div>
                    </div>
                </div>
                <a href="{{ route('contacts.index') }}" class="nav-link">Контакты</a>
            </nav>
            <div class="header-right">
                @if(auth()->check() && auth()->user()->isContentManager())
                    <nav class="header-nav header-nav-account" aria-label="Кабинет контент-менеджера">
                        <a href="{{ route('products.index') }}" class="nav-link nav-link-compact">Кабинет контент-менеджера</a>
                    </nav>
                @endif
                @if(auth()->check() && auth()->user()->isManager())
                    <nav class="header-nav header-nav-account" aria-label="Кабинет менеджера">
                        <a href="{{ route('manager.orders') }}" class="nav-link nav-link-compact">Кабинет менеджера</a>
                        <a href="{{ route('manager.feedback') }}" class="nav-link nav-link-compact">Обратная связь</a>
                    </nav>
                @endif
                @if(auth()->check() && auth()->user()->isDirector())
                    <nav class="header-nav header-nav-account" aria-label="Кабинет директора">
                        <a href="{{ route('director.dashboard') }}" class="nav-link nav-link-compact">Кабинет директора</a>
                    </nav>
                @endif
                @auth
                    @if(auth()->user()->isManager() || auth()->user()->isDirector())
                        <a href="{{ route('analytics.index') }}" class="nav-link nav-link-compact header-analytics-link">Аналитика</a>
                    @endif
                @endauth
                <a href="{{ route('cart.index') }}" class="nav-cart-link" aria-label="Корзина">
                    <span class="nav-cart-icon-wrap">
                        <svg class="nav-cart-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="9" cy="21" r="1"/>
                            <circle cx="20" cy="21" r="1"/>
                            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                        </svg>
                        @if(($cartItemCount ?? 0) > 0)
                            <span class="nav-cart-badge" aria-hidden="true"></span>
                        @endif
                    </span>
                </a>
                @auth
                    @if(auth()->user()->isCustomer())
                        <a href="{{ route('account.index') }}" class="nav-link nav-link-compact header-account-cabinet">Личный кабинет</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="header-logout-form">
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
        </div>
    </header>

    <main class="main-content">
        @yield('content')
    </main>

    @include('partials.site-footer')
</body>
</html>
