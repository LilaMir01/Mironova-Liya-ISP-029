@extends('layouts.app')

@section('title', 'Альта Дизайн — Фасадные материалы')
@section('meta_description', 'Альта Дизайн — интернет-магазин фасадных материалов. Сайдинг, фасадные панели, декоративный камень и другие решения для отделки дома.')
@section('meta_keywords', 'Альта Дизайн, фасадные материалы, сайдинг, фасадные панели, декоративный камень, купить материалы для фасада')

@section('content')
<section class="hero-banner">
    <div class="banner-left">
        <div class="banner-image-wrapper">
            <img src="{{ asset('images/vu-anh-ExOmPidaHvY-unsplash.jpg') }}" alt="Частный дом с фасадными материалами">
        </div>
        <div class="banner-left-content">
            <h1>Альта Дизайн</h1>
            <p class="company-desc">Профессиональные фасадные материалы для вашего дома. Качество, надёжность и современные решения для строительства и ремонта.</p>
            <a href="{{ route('catalog.index') }}" class="btn-learn-more">Перейти к покупкам</a>
        </div>
    </div>
</section>

@if(isset($homeSlides) && $homeSlides->isNotEmpty())
@php $homeSlideCount = $homeSlides->count(); @endphp
<section class="home-slider-section" aria-label="Галерея">
    <h2 class="home-slider-section__title">Особые предложения и акции</h2>
    <div class="home-slider" data-home-slider>
        <button type="button" class="home-slider__btn home-slider__btn--prev" data-home-slider-prev aria-label="Предыдущий слайд">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M15 18l-6-6 6-6"/></svg>
        </button>
        <div class="home-slider__viewport">
            <div
                class="home-slider__track"
                data-home-slider-track
                data-slide-count="{{ $homeSlideCount }}"
                style="width: {{ $homeSlideCount * 100 }}%;"
            >
                @foreach($homeSlides as $slide)
                    @php
                        $path = $slide->image_path ?? '';
                        $src = $path !== '' && ! str_starts_with($path, 'http') ? asset($path) : $path;
                        $slideFlexPct = $homeSlideCount > 0 ? 100 / $homeSlideCount : 100;
                    @endphp
                    <div class="home-slider__slide" style="flex: 0 0 {{ $slideFlexPct }}%; max-width: {{ $slideFlexPct }}%;">
                        <img src="{{ $src }}" alt="{{ $slide->alt_text ?? 'Слайд '.$slide->slot }}" loading="lazy">
                    </div>
                @endforeach
            </div>
        </div>
        <button type="button" class="home-slider__btn home-slider__btn--next" data-home-slider-next aria-label="Следующий слайд">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 18l6-6-6-6"/></svg>
        </button>
    </div>
    <div class="home-slider__dots" data-home-slider-dots aria-hidden="true"></div>
</section>
@endif

<section class="about-section" id="about">
    <div class="about-container">
        <h2>О нас</h2>
        <div class="about-content">
            <p>Компания «Альта Дизайн» — надёжный партнёр в сфере продажи фасадных материалов. Мы предлагаем широкий ассортимент продукции для облицовки зданий: сайдинг, фасадные панели, декоративный камень, штукатурные системы и многое другое.</p>
            <p>Наша информационная система помогает управлять заказами, каталогом и обратной связью с клиентами, а встроенный модуль аналитики позволяет отслеживать динамику продаж и принимать обоснованные бизнес-решения.</p>
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
