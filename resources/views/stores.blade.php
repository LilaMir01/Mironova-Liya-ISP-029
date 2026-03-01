@extends('layouts.app')

@section('title', 'Торговые точки и остатки — Альта Дизайн')

@section('content')
<div class="stores-page">
    <script>
        window.storesMaterials = @json($materialsJson);
    </script>

    @foreach($warehouses as $warehouse)
        <section class="stores-block">
            <h2>Склад: {{ $warehouse->name }}</h2>

            @if(auth()->user()?->isAdmin())
            <form method="POST" action="{{ route('stores.stock.store') }}" class="form-inline form-add-stock">
                @csrf
                <input type="hidden" name="warehouse_id" value="{{ $warehouse->id }}">
                <input type="hidden" name="material_id" class="material-id-input" value="">
                <div class="form-group autocomplete-wrap">
                    <label>Материал</label>
                    <input type="text"
                           class="material-search-input"
                           placeholder="Введите название, тип, производителя, цвет..."
                           autocomplete="off"
                           data-material-id="">
                    <div class="autocomplete-dropdown" role="listbox" aria-hidden="true"></div>
                </div>
                <div class="form-group">
                    <label>Количество</label>
                    <input type="number" name="quantity" min="1" value="1" required>
                </div>
                <button type="submit" class="btn-add">Добавить на склад</button>
            </form>
            @endif

            <table class="data-table">
                <thead>
                    <tr>
                        <th>Материал</th>
                        <th>Количество</th>
                        @if(auth()->user()?->isAdmin())
                        <th>Действия</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($warehouse->stocks as $stock)
                        <tr>
                            <td>{{ $stock->material->display_name ?? '—' }}</td>
                            <td>{{ $stock->quantity }}</td>
                            @if(auth()->user()?->isAdmin())
                            <td class="actions">
                                <a href="{{ route('stores.index', ['edit_stock' => $stock->id]) }}#stock-{{ $stock->id }}" class="btn-small btn-edit">Изменить</a>
                                <form method="POST" action="{{ route('stores.stock.destroy', $stock) }}" style="display: inline;" onsubmit="return confirm('Удалить запись об остатке?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-small btn-danger">Удалить</button>
                                </form>
                            </td>
                            @endif
                        </tr>
                    @empty
                        <tr class="empty-row"><td colspan="{{ auth()->user()?->isAdmin() ? 3 : 2 }}">На складе пока нет остатков.</td></tr>
                    @endforelse
                </tbody>
            </table>

            @if(auth()->user()?->isAdmin() && request('edit_stock'))
                @php $editStock = $warehouse->stocks->firstWhere('id', request('edit_stock')); @endphp
                @if($editStock)
                    <div class="form-inline" id="stock-{{ $editStock->id }}" style="background: #eff6ff; margin-top: 1rem;">
                        <form method="POST" action="{{ route('stores.stock.update', $editStock) }}">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label>Новое количество</label>
                                <input type="number" name="quantity" min="0" value="{{ $editStock->quantity }}" required>
                            </div>
                            <button type="submit" class="btn-add">Сохранить</button>
                            <a href="{{ route('stores.index') }}" class="btn-small btn-edit">Отмена</a>
                        </form>
                    </div>
                @endif
            @endif
        </section>
    @endforeach
</div>

<script>
(function() {
    const materials = window.storesMaterials || [];
    const searchInputs = document.querySelectorAll('.material-search-input');
    const idInputs = document.querySelectorAll('.material-id-input');

    function searchText(item) {
        return [item.type, item.manufacturer, item.productName, item.color, item.dimensions, item.displayName]
            .filter(Boolean).join(' ').toLowerCase();
    }

    function filterMaterials(query) {
        const q = query.trim().toLowerCase();
        if (!q) return materials;
        return materials.filter(function(item) {
            return searchText(item).indexOf(q) !== -1;
        });
    }

    function showDropdown(input, list) {
        const wrap = input.closest('.autocomplete-wrap');
        const dropdown = wrap.querySelector('.autocomplete-dropdown');
        dropdown.innerHTML = '';
        dropdown.setAttribute('aria-hidden', 'true');
        if (list.length === 0) {
            dropdown.classList.remove('open');
            return;
        }
        list.slice(0, 10).forEach(function(item) {
            const div = document.createElement('div');
            div.className = 'autocomplete-item';
            div.setAttribute('role', 'option');
            div.setAttribute('data-id', item.id);
            div.textContent = item.displayName;
            div.addEventListener('click', function() {
                input.value = item.displayName;
                input.setAttribute('data-material-id', item.id);
                const form = wrap.closest('form');
                const hidden = form.querySelector('.material-id-input');
                if (hidden) hidden.value = item.id;
                dropdown.classList.remove('open');
                dropdown.innerHTML = '';
            });
            dropdown.appendChild(div);
        });
        dropdown.classList.add('open');
        dropdown.setAttribute('aria-hidden', 'false');
    }

    function hideDropdown(input) {
        const wrap = input.closest('.autocomplete-wrap');
        const dropdown = wrap.querySelector('.autocomplete-dropdown');
        setTimeout(function() {
            dropdown.classList.remove('open');
            dropdown.innerHTML = '';
        }, 200);
    }

    searchInputs.forEach(function(input, i) {
        const form = input.closest('form');
        const hidden = form.querySelector('.material-id-input');

        input.addEventListener('input', function() {
            if (hidden) hidden.value = '';
            input.setAttribute('data-material-id', '');
            const list = filterMaterials(input.value);
            showDropdown(input, list);
        });

        input.addEventListener('focus', function() {
            const list = filterMaterials(input.value);
            showDropdown(input, list);
        });

        input.addEventListener('blur', function() {
            hideDropdown(input);
        });

        input.addEventListener('keydown', function(e) {
            const wrap = input.closest('.autocomplete-wrap');
            const dropdown = wrap.querySelector('.autocomplete-dropdown');
            const items = dropdown.querySelectorAll('.autocomplete-item');
            if (e.key === 'Escape') {
                dropdown.classList.remove('open');
                input.blur();
            }
            if (items.length && (e.key === 'ArrowDown' || e.key === 'ArrowUp' || e.key === 'Enter')) {
                e.preventDefault();
                const current = dropdown.querySelector('.autocomplete-item.highlight');
                let idx = current ? Array.prototype.indexOf.call(items, current) : -1;
                if (e.key === 'ArrowDown') idx = Math.min(idx + 1, items.length - 1);
                if (e.key === 'ArrowUp') idx = Math.max(idx - 1, 0);
                items.forEach(function(el, j) { el.classList.toggle('highlight', j === idx); });
                if (e.key === 'Enter' && idx >= 0 && items[idx]) {
                    items[idx].click();
                }
            }
        });

        form.addEventListener('submit', function(e) {
            if (!hidden || !hidden.value) {
                e.preventDefault();
                input.focus();
            }
        });
    });
})();
</script>
@endsection
