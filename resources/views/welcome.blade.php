@extends('layouts.app')

@section('title', 'Альта Дизайн — Фасадные материалы')

@section('content')
<section class="hero-banner">
    <div class="banner-bg"></div>
    <div class="banner-left">
        <div class="banner-image-wrapper">
            <img src="{{ asset('images/vu-anh-ExOmPidaHvY-unsplash.jpg') }}" alt="Частный дом с фасадными материалами">
        </div>
        <div class="banner-left-content">
            <h1>Альта Дизайн</h1>
            <p class="company-desc">Профессиональные фасадные материалы для вашего дома. Качество, надёжность и современные решения для строительства и ремонта.</p>
            <a href="#about" class="btn-learn-more">Узнать подробнее</a>
        </div>
    </div>

    <div class="banner-right">
        <div class="banner-right-gradient"></div>
        <div class="banner-right-content">
            <div class="banner-chart-svg">
                <svg viewBox="0 0 200 80" xmlns="http://www.w3.org/2000/svg">
                    <rect x="20" y="45" width="25" height="35" rx="4" fill="#ffffff" opacity="0.9"/>
                    <rect x="55" y="30" width="25" height="50" rx="4" fill="#ffffff" opacity="0.85"/>
                    <rect x="90" y="15" width="25" height="65" rx="4" fill="#ffffff" opacity="0.95"/>
                    <rect x="125" y="25" width="25" height="55" rx="4" fill="#ffffff" opacity="0.88"/>
                    <rect x="160" y="35" width="25" height="45" rx="4" fill="#ffffff" opacity="0.9"/>
                    <line x1="15" y1="75" x2="190" y2="75" stroke="#ffffff" stroke-width="1.5" opacity="0.5"/>
                </svg>
            </div>
            <h3>Управление и аналитика</h3>
            <p>На нашем портале вы можете проводить аналитику продаж, вести учёт поставок и остатков фасадных материалов в торговых точках компании.</p>
            <div class="banner-buttons">
                <a href="{{ route('products.index') }}">Товары</a>
                <a href="{{ route('stores.index') }}">Торговые точки и остатки</a>
                @if(auth()->user()?->isAdmin())
                <a href="{{ route('analytics.index') }}">Аналитика продаж</a>
                @endif
            </div>
        </div>
    </div>
</section>
<section class="about-section" id="about">
    <div class="about-container">
        <h2>О нас</h2>
        <div class="about-content">
            <p>Компания «Альта Дизайн» — надёжный партнёр в сфере продажи фасадных материалов. Мы предлагаем широкий ассортимент продукции для облицовки зданий: сайдинг, фасадные панели, декоративный камень, штукатурные системы и многое другое.</p>
            <p>Наша информационная система позволяет эффективно управлять поставками и остатками материалов в торговых точках, а встроенный модуль аналитики помогает отслеживать динамику продаж и принимать обоснованные бизнес-решения.</p>
        </div>
        <div class="offices-container">
            <div class="office-card">
                <img src="{{ asset('images/oneOne.png') }}" alt="Офис №1">
                <div class="office-caption">
                    <strong>Офис №1</strong>
                    <span>г. Сергиев Посад</span>
                </div>
            </div>
            <div class="office-card">
                <img src="{{ asset('images/twoTwo.png') }}" alt="Офис №2">
                <div class="office-caption">
                    <strong>Офис №2</strong>
                    <span>г. Хотьково</span>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
