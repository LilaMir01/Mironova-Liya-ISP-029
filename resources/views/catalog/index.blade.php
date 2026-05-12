@extends('layouts.app')

@section('title', 'Продукция — Альта Дизайн')
@section('meta_description', 'Каталог продукции Альта Дизайн: фасадные материалы, напольные покрытия, террасная доска, водосточные системы, утеплитель и изоляция.')
@section('meta_keywords', 'каталог фасадных материалов, продукция Альта Дизайн, сайдинг ПВХ, панели ПВХ, террасная доска, водосточные системы')

@section('content')
@php

    $imgSajdingPvh = 'images/catalog/sajding-pvh.png';
    $imgPaneliPvh = 'images/catalog/facsdes_pvh.png';
    $imgSajdingMetal = 'images/catalog/metal.png';
    $imgBitumnajaPlitka = 'images/catalog/bitum.png';
    $imgIskusstvennyjKamen = 'images/catalog/kamen.png';
    $imgSistemaUglovOkon = 'images/catalog/windowsandygol.png';
    $imgFibrocement = 'images/catalog/fibro.png';
    $imgSistemaKreplPvh = 'images/catalog/sistem.png';
    $imgTermopaneli = 'images/catalog/termo.png';
    $imgSistemaKreplFasadov = 'images/catalog/sistmenal.png';
    $imgFloor = 'images/catalog/pol.png';
    $imgTerrace = 'images/catalog/terr.png';
    $imgDrainage = 'images/catalog/vodos.png';
    $imgVentilation = 'images/catalog/vent.png';
    $imgInsulationIsolation = 'images/catalog/izo.png';
@endphp

<section class="catalog-page-simple">
    <h1 class="catalog-title-simple">Продукция</h1>

    <div class="catalog-grid-simple">
        <article class="catalog-card-simple"><img src="{{ $imgSajdingPvh }}" alt="Сайдинг ПВХ" loading="lazy"><div class="catalog-card-simple__content"><h3>Сайдинг ПВХ</h3><a href="{{ route('catalog.section', ['category' => 'facade', 'sectionSlug' => 'saiding-pvx']) }}">Подробнее</a></div></article>
        <article class="catalog-card-simple"><img src="{{ $imgPaneliPvh }}" alt="Панели ПВХ" loading="lazy"><div class="catalog-card-simple__content"><h3>Панели ПВХ</h3><a href="{{ route('catalog.section', ['category' => 'facade', 'sectionSlug' => 'paneli-pvx']) }}">Подробнее</a></div></article>
        <article class="catalog-card-simple"><img src="{{ $imgSajdingMetal }}" alt="Сайдинг металлический" loading="lazy"><div class="catalog-card-simple__content"><h3>Сайдинг металлический</h3><a href="{{ route('catalog.section', ['category' => 'facade', 'sectionSlug' => 'saiding-metalliceskii']) }}">Подробнее</a></div></article>
        <article class="catalog-card-simple"><img src="{{ $imgBitumnajaPlitka }}" alt="Битумная плитка" loading="lazy"><div class="catalog-card-simple__content"><h3>Битумная плитка</h3><a href="{{ route('catalog.section', ['category' => 'facade', 'sectionSlug' => 'bitumnaia-plitka']) }}">Подробнее</a></div></article>
        <article class="catalog-card-simple"><img src="{{ $imgIskusstvennyjKamen }}" alt="Искусственный камень" loading="lazy"><div class="catalog-card-simple__content"><h3>Искусственный камень</h3><a href="{{ route('catalog.section', ['category' => 'facade', 'sectionSlug' => 'iskusstvennyi-kamen']) }}">Подробнее</a></div></article>
        <article class="catalog-card-simple"><img src="{{ $imgSistemaUglovOkon }}" alt="Система отделки углов и окон" loading="lazy"><div class="catalog-card-simple__content"><h3>Система отделки углов и окон</h3><a href="{{ route('catalog.section', ['category' => 'facade', 'sectionSlug' => 'sistema-otdelki-uglov-i-okon']) }}">Подробнее</a></div></article>
        <article class="catalog-card-simple"><img src="{{ $imgFibrocement }}" alt="Фиброцементный сайдинг" loading="lazy"><div class="catalog-card-simple__content"><h3>Фиброцементный сайдинг</h3><a href="{{ route('catalog.section', ['category' => 'facade', 'sectionSlug' => 'fibrocementnyi-saiding']) }}">Подробнее</a></div></article>
        <article class="catalog-card-simple"><img src="{{ $imgSistemaKreplPvh }}" alt="Система крепления фасадов ПВХ" loading="lazy"><div class="catalog-card-simple__content"><h3>Система крепления фасадов ПВХ</h3><a href="{{ route('catalog.section', ['category' => 'facade', 'sectionSlug' => 'sistema-krepleniia-fasadov-pvx']) }}">Подробнее</a></div></article>
        <article class="catalog-card-simple"><img src="{{ $imgTermopaneli }}" alt="Термопанели" loading="lazy"><div class="catalog-card-simple__content"><h3>Термопанели</h3><a href="{{ route('catalog.section', ['category' => 'facade', 'sectionSlug' => 'termopaneli']) }}">Подробнее</a></div></article>
        <article class="catalog-card-simple"><img src="{{ $imgSistemaKreplFasadov }}" alt="Система крепления фасадов металл" loading="lazy"><div class="catalog-card-simple__content"><h3>Система крепления фасадов металл</h3><a href="{{ route('catalog.section', ['category' => 'facade', 'sectionSlug' => 'sistema-krepleniia-fasadov']) }}">Подробнее</a></div></article>
        <article class="catalog-card-simple"><img src="{{ $imgFloor }}" alt="Напольные покрытия" loading="lazy"><div class="catalog-card-simple__content"><h3>Напольные покрытия</h3><a href="{{ route('catalog.category', 'floor') }}">Подробнее</a></div></article>
        <article class="catalog-card-simple"><img src="{{ $imgTerrace }}" alt="Террасная доска" loading="lazy"><div class="catalog-card-simple__content"><h3>Террасная доска</h3><a href="{{ route('catalog.category', 'terrace') }}">Подробнее</a></div></article>
        <article class="catalog-card-simple"><img src="{{ $imgDrainage }}" alt="Водосточные системы" loading="lazy"><div class="catalog-card-simple__content"><h3>Водосточные системы</h3><a href="{{ route('catalog.category', 'drainage') }}">Подробнее</a></div></article>
        <article class="catalog-card-simple"><img src="{{ $imgVentilation }}" alt="Вентиляция" loading="lazy"><div class="catalog-card-simple__content"><h3>Вентиляция</h3><a href="{{ route('catalog.category', 'ventilation') }}">Подробнее</a></div></article>
        <article class="catalog-card-simple"><img src="{{ $imgInsulationIsolation }}" alt="Утеплитель и изоляция" loading="lazy"><div class="catalog-card-simple__content"><h3>Утеплитель и изоляция</h3><a href="{{ route('catalog.category', 'insulation') }}">Подробнее</a></div></article>
    </div>
</section>

@endsection
