@extends('layouts.app')

@section('title', $categoryTitle . ' — Альта Дизайн')
@section('meta_description', $categoryTitle . ' в каталоге Альта Дизайн: актуальные цены, характеристики и подбор материалов.')
@section('meta_keywords', $categoryTitle . ', купить ' . $categoryTitle . ', цена ' . $categoryTitle . ', каталог, Альта Дизайн')

@section('content')
<section class="catalog-products-page" data-catalog-products-page>
    <h1 class="catalog-section-title">{{ $categoryTitle }}</h1>
    <div class="catalog-layout">
        <aside class="catalog-filter">
            <div class="filter-panel-header">
                <div class="filter-panel-title">
                    <h3>Фильтр</h3>
                    <span class="filter-panel-accent"></span>
                </div>
            </div>

            <form id="catalog-filter-form" method="GET" class="catalog-filter-form-inner">
                <div class="filter-block">
                    <div class="filter-block-label">
                        <span class="filter-block-title">Цена</span>
                    </div>

                    <div
                        class="js-price-range price-range"
                        data-min="{{ (int) $minPrice }}"
                        data-max="{{ (int) $maxPrice }}"
                    >
                        <div class="price-range__track-wrap">
                            <div class="price-range__rail"></div>
                            <input
                                type="range"
                                class="price-range__input price-range__input--min js-range-from"
                                min="{{ (int) $minPrice }}"
                                max="{{ (int) $maxPrice }}"
                                value="{{ (int) $priceFrom }}"
                                aria-label="Цена от"
                            >
                            <input
                                type="range"
                                class="price-range__input price-range__input--max js-range-to"
                                min="{{ (int) $minPrice }}"
                                max="{{ (int) $maxPrice }}"
                                value="{{ (int) $priceTo }}"
                                aria-label="Цена до"
                            >
                        </div>
                        <div class="price-range__inputs">
                            <div class="price-range__field">
                                <input type="number" name="price_from" id="price_from" value="{{ (int) $priceFrom }}" min="{{ (int) $minPrice }}" max="{{ (int) $maxPrice }}">
                                <span class="price-range__currency">р.</span>
                            </div>
                            <div class="price-range__field">
                                <input type="number" name="price_to" id="price_to" value="{{ (int) $priceTo }}" min="{{ (int) $minPrice }}" max="{{ (int) $maxPrice }}">
                                <span class="price-range__currency">р.</span>
                            </div>
                        </div>
                    </div>
                </div>

                <p class="manufacturer-empty">Фильтр по производителям для этой категории пока не настроен.</p>

                <button type="submit" class="btn-add filter-apply-btn">Применить</button>
            </form>
        </aside>

        <div class="product-cards">
            @forelse($materials as $material)
                <article class="product-card">
                    <img src="{{ $material->image_url ?? 'https://via.placeholder.com/320x220?text=Alta+Design' }}" alt="{{ $material->product_name }}">
                    <h4>{{ $material->product_name }}</h4>
                    <p class="product-card__price">{{ number_format((float) $material->price, 0, '.', ',') }} ₽</p>
                </article>
            @empty
                <p class="empty-products-note">Карточки товаров для этой категории пока не заполнены.</p>
            @endforelse
        </div>
    </div>
</section>
@endsection

