@extends('layouts.app')

@section('title', $section->name . ' — Альта Дизайн')
@section('meta_description', $section->name . ' в каталоге Альта Дизайн: актуальные цены, характеристики, фильтр по производителям и подбор материалов для фасада.')
@section('meta_keywords', $section->name . ', купить ' . $section->name . ', цена ' . $section->name . ', фасадные материалы, Альта Дизайн')

@section('content')
<section class="catalog-products-page" data-catalog-products-page>
    <h1 class="catalog-section-title">{{ $section->name }}</h1>
    <div class="catalog-layout">
        <aside class="catalog-filter">
            <div class="filter-panel-header">
                <div class="filter-panel-title">
                    <h3>Фильтр</h3>
                    <span class="filter-panel-accent"></span>
                </div>
                <button type="button" class="filter-clear-btn js-price-clear" form="catalog-filter-form">Очистить</button>
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

                <div class="form-group filter-manufacturer">
                    <label>Производители</label>
                    <div class="manufacturer-list">
                        @forelse($manufacturers as $manufacturer)
                            <a
                                href="{{ route('catalog.section', ['category' => 'facade', 'sectionSlug' => $section->slug, 'price_from' => (int) $priceFrom, 'price_to' => (int) $priceTo, 'manufacturer_id' => $manufacturer->id]) }}"
                                class="manufacturer-item {{ (int) $manufacturerId === (int) $manufacturer->id ? 'is-active' : '' }}"
                            >
                                <img src="{{ $manufacturer->logo_url ?: 'https://via.placeholder.com/46x46?text=LOGO' }}" alt="{{ $manufacturer->name }}">
                                <span>{{ $manufacturer->name }}</span>
                            </a>
                        @empty
                            <p class="manufacturer-empty">Пока нет производителей для этого раздела.</p>
                        @endforelse
                    </div>
                </div>
                <button type="submit" class="btn-add filter-apply-btn">Применить</button>
            </form>
        </aside>

        <div class="product-cards">
            @forelse($materials as $material)
                <article
                    class="product-card product-card--clickable js-catalog-product-card"
                    data-material-id="{{ $material->id }}"
                    role="button"
                    tabindex="0"
                    aria-label="Открыть карточку: {{ $material->product_name }}"
                >
                    <img src="{{ $material->image_url ?? 'https://via.placeholder.com/320x220?text=Alta+Design' }}" alt="{{ $material->product_name }}">
                    <h4>{{ $material->product_name }}</h4>
                    <p class="product-card__price">{{ number_format((float) $material->price, 0, '.', ',') }} ₽</p>
                    <small class="product-card__brand">{{ $material->manufacturer?->name }}</small>
                    <form method="POST" action="{{ route('cart.add', $material) }}" class="product-card__cart js-catalog-product-card-cart">
                        @csrf
                        <label class="product-card__cart-qty-label">
                            <span class="product-card__cart-qty-text">Количество</span>
                            <input type="number" name="qty" min="1" max="9999" value="1" class="product-card__cart-qty js-qty-restricted" inputmode="numeric" autocomplete="off" aria-label="Количество">
                        </label>
                        <button type="submit" class="product-card__cart-btn" aria-label="В корзину" title="В корзину">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <circle cx="9" cy="21" r="1"/>
                                <circle cx="20" cy="21" r="1"/>
                                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                            </svg>
                        </button>
                    </form>
                </article>
            @empty
                <p class="empty-products-note">Карточки товаров для этого раздела пока не заполнены.</p>
            @endforelse
        </div>
    </div>

    <script type="application/json" id="catalog-products-modal-data">@json($catalogProductsModalData)</script>

    <div class="product-detail-modal" id="product-detail-modal" hidden>
        <div class="product-detail-modal__backdrop js-product-detail-modal-close" aria-hidden="true"></div>
        <div class="product-detail-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="product-detail-modal-title">
            <button type="button" class="product-detail-modal__close js-product-detail-modal-close" aria-label="Закрыть">&times;</button>
            <div class="product-detail-modal__layout">
                <div class="product-detail-modal__media">
                    <img src="" alt="" class="product-detail-modal__img js-product-detail-modal-img" width="480" height="360">
                </div>
                <div class="product-detail-modal__body">
                    <h2 id="product-detail-modal-title" class="product-detail-modal__title js-product-detail-modal-title"></h2>
                    <p class="product-detail-modal__price js-product-detail-modal-price"></p>
                    <div class="product-detail-modal__meta">
                        <div class="product-detail-modal__meta-row">
                            <span class="product-detail-modal__meta-label">Производитель</span>
                            <span class="product-detail-modal__meta-value js-product-detail-modal-manufacturer"></span>
                        </div>
                        <div class="product-detail-modal__meta-row">
                            <span class="product-detail-modal__meta-label">Цвет</span>
                            <span class="product-detail-modal__meta-value js-product-detail-modal-color"></span>
                        </div>
                        <div class="product-detail-modal__meta-row">
                            <span class="product-detail-modal__meta-label">Габариты</span>
                            <span class="product-detail-modal__meta-value js-product-detail-modal-dimensions"></span>
                        </div>
                    </div>
                    <div class="product-detail-modal__description-block">
                        <h3 class="product-detail-modal__description-label">Описание</h3>
                        <p class="product-detail-modal__description js-product-detail-modal-description"></p>
                    </div>
                    <form method="POST" class="product-detail-modal__cart-form js-product-detail-modal-form">
                        @csrf
                        <label class="product-detail-modal__qty-label">
                            <span>Количество</span>
                            <input type="number" name="qty" min="1" max="9999" value="1" class="product-detail-modal__qty-input js-qty-restricted" inputmode="numeric" autocomplete="off" aria-label="Количество">
                        </label>
                        <button type="submit" class="btn-add product-detail-modal__submit">В корзину</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
