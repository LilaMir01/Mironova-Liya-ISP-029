@extends('layouts.app')

@section('title', 'Товары — Альта Дизайн')

@section('content')
<div class="stores-page products-page">

    <section class="stores-block">
        @if(auth()->user()?->isAdmin())
        <h2>Добавить тип и производителя</h2>
        <div class="form-inline">
            <form method="POST" action="{{ route('products.material-types.store') }}" style="display: flex; gap: 0.5rem; align-items: flex-end;">
                @csrf
                <div class="form-group">
                    <label>Новый тип материала</label>
                    <input type="text" name="name" placeholder="Напр. Сайдинг" required>
                </div>
                <button type="submit" class="btn-add">Добавить тип</button>
            </form>
            <form method="POST" action="{{ route('products.manufacturers.store') }}" style="display: flex; gap: 0.5rem; align-items: flex-end;">
                @csrf
                <div class="form-group">
                    <label>Новый производитель</label>
                    <input type="text" name="name" placeholder="Напр. Альта Профиль" required>
                </div>
                <button type="submit" class="btn-add">Добавить производителя</button>
            </form>
        </div>

        <h2 style="margin-top: 1.5rem;">Добавить товар</h2>
        @if(isset($editMaterial))
            <div class="form-inline form-edit" style="background: #eff6ff; margin-bottom: 1rem;">
                <form method="POST" action="{{ route('products.materials.update', $editMaterial) }}" style="display: flex; flex-wrap: wrap; gap: 0.75rem; align-items: flex-end;">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label>Тип</label>
                        <select name="material_type_id" required>
                            @foreach($materialTypes as $t)
                                <option value="{{ $t->id }}" {{ $editMaterial->material_type_id == $t->id ? 'selected' : '' }}>{{ $t->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Производитель</label>
                        <select name="manufacturer_id" required>
                            @foreach($manufacturers as $m)
                                <option value="{{ $m->id }}" {{ $editMaterial->manufacturer_id == $m->id ? 'selected' : '' }}>{{ $m->name }}</option>
                            @endforeach
                        </select>
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
                        <label>Цена (₽)</label>
                        <input type="number" name="price" step="0.01" min="0" value="{{ old('price', $editMaterial->price) }}" required>
                    </div>
                    <button type="submit" class="btn-add">Сохранить</button>
                    <a href="{{ route('products.index') }}" class="btn-small btn-edit">Отмена</a>
                </form>
            </div>
        @else
            <form method="POST" action="{{ route('products.materials.store') }}" class="form-inline" style="margin-bottom: 1.5rem;">
                @csrf
                <div class="form-group">
                    <label>Тип</label>
                    <select name="material_type_id" required>
                        <option value="">— выберите —</option>
                        @foreach($materialTypes as $t)
                            <option value="{{ $t->id }}" {{ old('material_type_id') == $t->id ? 'selected' : '' }}>{{ $t->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Производитель</label>
                    <select name="manufacturer_id" required>
                        <option value="">— выберите —</option>
                        @foreach($manufacturers as $m)
                            <option value="{{ $m->id }}" {{ old('manufacturer_id') == $m->id ? 'selected' : '' }}>{{ $m->name }}</option>
                        @endforeach
                    </select>
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
                    <label>Цена (₽)</label>
                    <input type="number" name="price" step="0.01" min="0" value="{{ old('price', '') }}" required>
                </div>
                <button type="submit" class="btn-add">Добавить товар</button>
            </form>
        @endif
        @endif

        <table class="data-table catalogue-table">
            <thead>
                <tr>
                    <th>Тип</th>
                    <th>Производитель</th>
                    <th>Название</th>
                    <th>Цвет</th>
                    <th>Габариты</th>
                    <th>Цена (₽)</th>
                    @if(auth()->user()?->isAdmin())
                    <th>Действия</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($materials as $m)
                    <tr>
                        <td>{{ $m->materialType->name ?? '—' }}</td>
                        <td>{{ $m->manufacturer->name ?? '—' }}</td>
                        <td>{{ $m->product_name }}</td>
                        <td>{{ $m->color ?? '—' }}</td>
                        <td>{{ $m->dimensions ?? '—' }}</td>
                        <td>{{ number_format($m->price, 2) }}</td>
                        @if(auth()->user()?->isAdmin())
                        <td class="actions">
                            <a href="{{ route('products.index', ['edit' => $m->id]) }}" class="btn-small btn-edit">Изменить</a>
                            <form method="POST" action="{{ route('products.materials.destroy', $m) }}" style="display: inline;" onsubmit="return confirm('Удалить товар из справочника?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-small btn-danger">Удалить</button>
                            </form>
                        </td>
                        @endif
                    </tr>
                @empty
                    <tr class="empty-row"><td colspan="{{ auth()->user()?->isAdmin() ? 7 : 6 }}">Нет товаров.</td></tr>
                @endforelse
            </tbody>
        </table>
    </section>
</div>
@endsection
