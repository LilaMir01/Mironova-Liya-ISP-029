@extends('layouts.app')

@section('title', 'Товары — Альта Дизайн')

@section('content')
@php
    $dimensionUnits = ['mm' => 'мм', 'см' => 'см', 'м' => 'м', 'м²' => 'м²', 'м³' => 'м³'];
@endphp
<div class="stores-page products-page" data-products-creator>

    @include('partials.flash-success', ['extraClass' => 'site-flash-success--mb'])

    @if(auth()->user()?->isContentManager())
    <h1 class="cabinet-main-title">Личный кабинет контент-менеджера</h1>
    <section class="stores-block">
        <h2>Слайдер на главной странице</h2>
        <p class="home-slides-hint">
            Рекомендуемый размер изображения: 1496×460 px. Формат: JPG, PNG, WEBP.
        </p>
        <form method="POST" action="{{ route('products.home-slides.update') }}" enctype="multipart/form-data" class="form-inline form-inline--plain home-slides-form">
            @csrf
            @method('PUT')
            <div class="home-slides-form__slides-row">
                @foreach($homeSlides ?? [] as $slide)
                    <div class="form-group home-slide-file-group">
                        <label for="home-slide-file-{{ $slide->slot }}">Слайд {{ $slide->slot }}</label>
                        <label class="creator-file creator-file--slide-only">
                            <span class="creator-file__btn creator-file__btn--slide">Выбрать слайд</span>
                            <input id="home-slide-file-{{ $slide->slot }}" type="file" name="slide_file_{{ $slide->slot }}" accept="image/jpeg,image/png,image/webp,image/gif" class="creator-file__input js-creator-file-input js-home-slide-input">
                        </label>
                        <div class="home-slide-preview-wrap">
                            <div class="home-slide-preview__empty js-home-slide-preview-empty" @if($slide->image_url) hidden @endif></div>
                            <img src="{{ $slide->image_url ?? '' }}" alt="" class="home-slide-preview__img js-home-slide-preview-img" width="120" height="72" loading="lazy" @if(empty($slide->image_url)) hidden @endif>
                        </div>
                    </div>
                @endforeach
                <div class="home-slides-form__save-cell">
                    <button type="submit" class="btn-add">Сохранить слайдер</button>
                </div>
            </div>
        </form>
    </section>
    @endif

    <section class="stores-block">
        @if(auth()->user()?->isContentManager())
        <h2>Добавить материал или производителя</h2>
        <div class="form-inline form-inline--plain creator-add-row">
            <form method="POST" action="{{ route('products.material-types.store') }}" class="creator-add-form">
                @csrf
                <div class="form-group">
                    <label>Новый материал</label>
                    <input type="text" name="name" placeholder="Напр. Сайдинг" required>
                </div>
                <button type="submit" class="btn-add">Добавить материал</button>
            </form>
            <form method="POST" action="{{ route('products.manufacturers.store') }}" enctype="multipart/form-data" class="creator-add-form">
                @csrf
                <div class="form-group">
                    <label>Новый производитель</label>
                    <input type="text" name="name" placeholder="Напр. Альта Профиль" required>
                </div>
                <div class="form-group">
                    <label>Логотип</label>
                    <label class="creator-file">
                        <span class="creator-file__btn creator-file__btn--slide">Выбрать файл</span>
                        <span class="creator-file__name js-creator-file-name">Файл не выбран</span>
                        <input type="file" name="logo" accept="image/jpeg,image/png,image/webp,image/gif" class="creator-file__input js-creator-file-input">
                    </label>
                </div>
                <button type="submit" class="btn-add">Добавить производителя</button>
            </form>
        </div>

        <h2 class="creator-delete-heading">Удалить материал или производителя</h2>
        <div class="creator-delete-grid">
            <div class="creator-delete-col">
                <h3 class="creator-delete-col__title">Выберите материал</h3>
                <div class="creator-combo" data-combo-role="material-type">
                    <input type="text" class="creator-combo__input" autocomplete="off" placeholder="Материал" aria-autocomplete="list" aria-expanded="false">
                    <ul class="creator-combo__list" hidden role="listbox"></ul>
                </div>
                <form method="POST" class="js-confirm-delete js-creator-delete-with-action creator-delete-form" data-entity-label="материал" data-url-base="{{ url('products/material-types') }}" data-selected-id="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-creator-danger" disabled>Удалить</button>
                </form>
            </div>
            <div class="creator-delete-col">
                <h3 class="creator-delete-col__title">Выберите производителя</h3>
                <div class="creator-combo" data-combo-role="manufacturer">
                    <input type="text" class="creator-combo__input" autocomplete="off" placeholder="Производитель" aria-autocomplete="list" aria-expanded="false">
                    <ul class="creator-combo__list" hidden role="listbox"></ul>
                </div>
                <form method="POST" class="js-confirm-delete js-creator-delete-with-action creator-delete-form" data-entity-label="производителя" data-url-base="{{ url('products/manufacturers') }}" data-selected-id="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-creator-danger" disabled>Удалить</button>
                </form>
            </div>
        </div>

        <script type="application/json" id="creator-json-material-types">@json($materialTypes->map(fn ($t) => ['id' => $t->id, 'name' => $t->name]))</script>
        <script type="application/json" id="creator-json-manufacturers">@json($manufacturers->map(fn ($m) => ['id' => $m->id, 'name' => $m->name]))</script>

        @php
            $addMaterialTypeId = old('material_type_id');
            $addMaterialTypeName = $addMaterialTypeId ? ($materialTypes->firstWhere('id', $addMaterialTypeId)->name ?? '') : '';
            $addManufacturerId = old('manufacturer_id');
            $addManufacturerName = $addManufacturerId ? ($manufacturers->firstWhere('id', $addManufacturerId)->name ?? '') : '';
        @endphp
        <h2 id="creator-product-form" style="margin-top: 1.5rem;">Добавить товар</h2>
        @if(isset($editMaterial))
            @php
                $editTypeId = old('material_type_id', $editMaterial->material_type_id);
                $editTypeName = $materialTypes->firstWhere('id', $editTypeId)?->name ?? '';
                $editManufId = old('manufacturer_id', $editMaterial->manufacturer_id);
                $editManufName = $manufacturers->firstWhere('id', $editManufId)?->name ?? '';
            @endphp
            <div class="form-inline form-inline--plain form-edit" style="margin-bottom: 1rem;">
                <form method="POST" action="{{ route('products.materials.update', $editMaterial) }}" enctype="multipart/form-data" class="creator-add-product-form-inner" style="display: flex; flex-wrap: wrap; gap: 0.75rem; align-items: flex-end;">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label>Материал</label>
                        <div class="creator-combo" data-add-combo="material">
                            <input type="text" class="creator-combo__input" value="{{ $editTypeName }}" autocomplete="off" placeholder="Материал" aria-autocomplete="list" aria-expanded="false">
                            <ul class="creator-combo__list" hidden role="listbox"></ul>
                        </div>
                        <input type="hidden" name="material_type_id" class="js-add-product-type-id" value="{{ $editTypeId }}">
                    </div>
                    <div class="form-group">
                        <label>Производитель</label>
                        <div class="creator-combo" data-add-combo="manufacturer">
                            <input type="text" class="creator-combo__input" value="{{ $editManufName }}" autocomplete="off" placeholder="Производитель" aria-autocomplete="list" aria-expanded="false">
                            <ul class="creator-combo__list" hidden role="listbox"></ul>
                        </div>
                        <input type="hidden" name="manufacturer_id" class="js-add-product-manufacturer-id" value="{{ $editManufId }}">
                    </div>
                    <div class="form-group">
                        <label>Название продукта</label>
                        <input type="text" name="product_name" value="{{ old('product_name', $editMaterial->product_name) }}" required>
                    </div>
                    <div class="form-group">
                        <label>Цвет</label>
                        <input type="text" name="color" value="{{ old('color', $editMaterial->color) }}">
                    </div>
                    <div class="form-group">
                        <label>Габариты</label>
                        <input type="text" name="dimensions" value="{{ old('dimensions', $editMaterial->dimensions) }}" placeholder="3 x 0,205 м">
                    </div>
                    <div class="form-group">
                        <label for="edit_dimensions_unit">Ед. измерения</label>
                        <div class="creator-combo">
                            <select id="edit_dimensions_unit" name="dimensions_unit" class="creator-combo__input creator-unit-select">
                                <option value="">—</option>
                                @foreach($dimensionUnits as $unitValue => $unitLabel)
                                    <option value="{{ $unitValue }}" {{ old('dimensions_unit', $editMaterial->dimensions_unit) === $unitValue ? 'selected' : '' }}>{{ $unitLabel }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Цена (₽)</label>
                        <input type="number" name="price" step="0.01" min="0" value="{{ old('price', $editMaterial->price) }}" required>
                    </div>
                    <div class="form-group form-group--full-row">
                        <label for="edit_material_description">Описание</label>
                        <textarea id="edit_material_description" name="description" class="creator-description-field" rows="6" placeholder="Текст для карточки товара (виден только в окне при нажатии на товар в каталоге)">{{ old('description', $editMaterial->description) }}</textarea>
                    </div>
                    <div class="creator-add-product-actions">
                        <div class="form-group js-product-photo-field">
                            <label>Фото товара</label>
                            <div class="creator-product-preview-row">
                                <img src="{{ $editMaterial->image_url ?? '' }}" alt="" class="creator-product-preview__img js-product-photo-preview" width="120" height="120" loading="lazy" @if(empty($editMaterial->image_url)) hidden @endif>
                                <label class="creator-file">
                                    <span class="creator-file__btn creator-file__btn--slide">Выбрать файл</span>
                                    <span class="creator-file__name js-creator-file-name">{{ $editMaterial->image_path ? 'Текущее изображение' : 'Файл не выбран' }}</span>
                                    <input type="file" name="image" accept="image/jpeg,image/png,image/webp,image/gif" class="creator-file__input js-creator-file-input js-product-photo-file">
                                </label>
                            </div>
                        </div>
                        <button type="submit" class="btn-add">Сохранить</button>
                    </div>
                </form>
            </div>
        @else
            <form method="POST" action="{{ route('products.materials.store') }}" enctype="multipart/form-data" class="form-inline form-inline--plain creator-add-product-form" style="margin-bottom: 1.5rem;">
                @csrf
                <div class="form-group">
                    <label>Материал</label>
                    <div class="creator-combo" data-add-combo="material">
                        <input type="text" class="creator-combo__input" value="{{ $addMaterialTypeName }}" autocomplete="off" placeholder="Материал" aria-autocomplete="list" aria-expanded="false">
                        <ul class="creator-combo__list" hidden role="listbox"></ul>
                    </div>
                    <input type="hidden" name="material_type_id" class="js-add-product-type-id" value="{{ $addMaterialTypeId }}">
                </div>
                <div class="form-group">
                    <label>Производитель</label>
                    <div class="creator-combo" data-add-combo="manufacturer">
                        <input type="text" class="creator-combo__input" value="{{ $addManufacturerName }}" autocomplete="off" placeholder="Производитель" aria-autocomplete="list" aria-expanded="false">
                        <ul class="creator-combo__list" hidden role="listbox"></ul>
                    </div>
                    <input type="hidden" name="manufacturer_id" class="js-add-product-manufacturer-id" value="{{ $addManufacturerId }}">
                </div>
                <div class="form-group">
                    <label>Название продукта</label>
                    <input type="text" name="product_name" value="{{ old('product_name') }}" placeholder="Аляска Ивори" required>
                </div>
                <div class="form-group">
                    <label>Цвет</label>
                    <input type="text" name="color" value="{{ old('color') }}">
                </div>
                <div class="form-group">
                    <label>Габариты</label>
                    <input type="text" name="dimensions" value="{{ old('dimensions') }}" placeholder="3 x 0,205 м">
                </div>
                <div class="form-group">
                    <label for="add_dimensions_unit">Ед. измерения</label>
                    <div class="creator-combo">
                        <select id="add_dimensions_unit" name="dimensions_unit" class="creator-combo__input creator-unit-select">
                            <option value="">—</option>
                            @foreach($dimensionUnits as $unitValue => $unitLabel)
                                <option value="{{ $unitValue }}" {{ old('dimensions_unit') === $unitValue ? 'selected' : '' }}>{{ $unitLabel }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label>Цена (₽)</label>
                    <input type="number" name="price" step="0.01" min="0" value="{{ old('price', '') }}" required>
                </div>
                <div class="form-group form-group--full-row">
                    <label for="add_material_description">Описание</label>
                    <textarea id="add_material_description" name="description" class="creator-description-field" rows="6" placeholder="Текст для карточки товара (виден только в окне при нажатии на товар в каталоге)">{{ old('description') }}</textarea>
                </div>
                <div class="creator-add-product-actions">
                    <div class="form-group js-product-photo-field">
                        <label>Фото товара</label>
                        <div class="creator-product-preview-row">
                            <img src="" alt="" class="creator-product-preview__img js-product-photo-preview" width="120" height="120" hidden>
                            <label class="creator-file">
                                <span class="creator-file__btn creator-file__btn--slide">Выбрать файл</span>
                                <span class="creator-file__name js-creator-file-name">Файл не выбран</span>
                                <input type="file" name="image" accept="image/jpeg,image/png,image/webp,image/gif" class="creator-file__input js-creator-file-input js-product-photo-file">
                            </label>
                        </div>
                    </div>
                    <button type="submit" class="btn-add">Добавить товар</button>
                </div>
            </form>
        @endif
        @endif

        @if(auth()->user()?->isContentManager())
        <h2>Все товары</h2>
        <form method="GET" action="{{ route('products.index') }}" class="products-filter-bar form-inline form-inline--plain">
            @if(request()->has('edit'))
                <input type="hidden" name="edit" value="{{ request('edit') }}">
            @endif
            <div class="form-group">
                <label for="filter_material_type_id">Материал</label>
                <select name="filter_material_type_id" id="filter_material_type_id">
                    <option value="">Все</option>
                    @foreach($materialTypes as $t)
                        <option value="{{ $t->id }}" {{ (string) request('filter_material_type_id') === (string) $t->id ? 'selected' : '' }}>{{ $t->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="filter_manufacturer_id">Производитель</label>
                <select name="filter_manufacturer_id" id="filter_manufacturer_id">
                    <option value="">Все</option>
                    @foreach($manufacturers as $m)
                        <option value="{{ $m->id }}" {{ (string) request('filter_manufacturer_id') === (string) $m->id ? 'selected' : '' }}>{{ $m->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="filter_product_name">Название товара</label>
                <select name="filter_product_name" id="filter_product_name">
                    <option value="">Все</option>
                    @foreach($productNamesForFilter ?? [] as $pname)
                        <option value="{{ $pname }}" {{ request('filter_product_name') === $pname ? 'selected' : '' }}>{{ $pname }}</option>
                    @endforeach
                </select>
            </div>
            <div class="products-filter-bar__actions">
                <button type="submit" class="btn-add">Найти</button>
                <a href="{{ route('products.index', array_filter(['edit' => request('edit')])) }}" class="products-filter-reset">Сбросить</a>
            </div>
        </form>
        @endif

        <table class="data-table catalogue-table products-catalogue-table">
            <thead>
                <tr>
                    @if(auth()->user()?->isContentManager())
                    <th>Фото</th>
                    @endif
                    <th>Материал</th>
                    <th>Производитель</th>
                    <th>Название</th>
                    <th>Цвет</th>
                    <th>Габариты</th>
                    <th>Цена (₽)</th>
                    @if(auth()->user()?->isContentManager())
                    <th>Действия</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($materials as $m)
                    <tr>
                        @if(auth()->user()?->isContentManager())
                        <td class="products-table__img-cell">
                            @if($m->image_url)
                                <img src="{{ $m->image_url }}" alt="" class="products-table__thumb" width="64" height="64" loading="lazy">
                            @else
                                <span class="products-table__no-img">—</span>
                            @endif
                        </td>
                        @endif
                        <td>{{ $m->materialType->name ?? '—' }}</td>
                        <td>{{ $m->manufacturer->name ?? '—' }}</td>
                        <td>{{ $m->product_name }}</td>
                        <td>{{ $m->color ?? '—' }}</td>
                        <td>{{ $m->dimensions_label ?? '—' }}</td>
                        <td>{{ number_format((float) $m->price, 0, '.', ',') }}</td>
                        @if(auth()->user()?->isContentManager())
                        <td class="actions">
                            <a href="{{ route('products.index', ['edit' => $m->id]) }}#creator-product-form" class="btn-small btn-edit">Изменить</a>
                            <form method="POST" action="{{ route('products.materials.destroy', $m) }}" style="display: inline;" class="js-confirm-delete" data-entity-label="товар">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-small btn-danger">Удалить</button>
                            </form>
                        </td>
                        @endif
                    </tr>
                @empty
                    <tr class="empty-row"><td colspan="{{ auth()->user()?->isContentManager() ? 8 : 6 }}">Нет товаров.</td></tr>
                @endforelse
            </tbody>
        </table>

        @if(auth()->user()?->isContentManager() && $materials->hasPages())
            <div class="products-pagination">
                {{ $materials->withQueryString()->links('pagination::default') }}
            </div>
        @endif

        @if(auth()->user()?->isContentManager())
        <h2>Все производители</h2>
        <table class="data-table catalogue-table products-catalogue-table">
            <thead>
                <tr>
                    <th>Логотип</th>
                    <th>Название</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                @forelse($manufacturers as $manufacturer)
                    <tr>
                        <td class="products-table__img-cell">
                            @if($manufacturer->logo_url)
                                <img src="{{ $manufacturer->logo_url }}" alt="{{ $manufacturer->name }}" class="products-table__thumb" width="64" height="64" loading="lazy">
                            @else
                                <span class="products-table__no-img">—</span>
                            @endif
                        </td>
                        <td>
                            <form method="POST" action="{{ route('products.manufacturers.update', $manufacturer) }}" enctype="multipart/form-data" class="manufacturer-edit-form">
                                @csrf
                                @method('PUT')
                                <input type="text" name="name" value="{{ $manufacturer->name }}" required>
                                <label class="creator-file">
                                    <span class="creator-file__btn creator-file__btn--slide">Выбрать файл</span>
                                    <span class="creator-file__name js-creator-file-name">Файл не выбран</span>
                                    <input type="file" name="logo" accept="image/jpeg,image/png,image/webp,image/gif" class="creator-file__input js-creator-file-input">
                                </label>
                                <button type="submit" class="btn-small btn-edit">Сохранить</button>
                            </form>
                        </td>
                        <td class="actions">
                            <form method="POST" action="{{ route('products.manufacturers.destroy', $manufacturer) }}" style="display: inline;" class="js-confirm-delete" data-entity-label="производителя">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-small btn-danger">Удалить</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr class="empty-row"><td colspan="3">Нет производителей.</td></tr>
                @endforelse
            </tbody>
        </table>
        @endif
    </section>
</div>
@endsection
