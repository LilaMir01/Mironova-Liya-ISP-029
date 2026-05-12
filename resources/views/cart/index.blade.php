@extends('layouts.app')

@section('title', 'Корзина — Альта Дизайн')
@section('meta_description', 'Корзина интернет-магазина Альта Дизайн: проверка товаров, изменение количества и оформление заказа фасадных материалов онлайн.')
@section('meta_keywords', 'корзина, оформить заказ, фасадные материалы, купить онлайн, Альта Дизайн')

@section('content')
<section class="catalog-page" data-cart-page>
    <h1>Корзина</h1>

    @include('partials.flash-success', ['extraClass' => 'site-flash-success--mb'])

    @if($errors->any())
        <ul class="auth-errors">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <table class="data-table">
        <thead>
            <tr>
                <th>Изображение</th>
                <th>Материал</th>
                <th>Количество</th>
                <th>Цена</th>
                <th>Сумма</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($cart as $item)
                <tr data-cart-row data-unit-price="{{ (float) $item['price'] }}">
                    <td>
                        @php
                            $cartImg = $item['image_path'] ?? '';
                            $cartSrc = $cartImg === '' ? null : ((str_starts_with($cartImg, 'http://') || str_starts_with($cartImg, 'https://')) ? $cartImg : asset($cartImg));
                        @endphp
                        <img src="{{ $cartSrc ?? 'https://via.placeholder.com/80x60?text=Material' }}" alt="{{ $item['name'] }}" style="width: 80px; height: 60px; object-fit: cover;">
                    </td>
                    <td>{{ $item['name'] }}</td>
                    <td>
                        <form method="POST" action="{{ route('cart.update', $item['id']) }}" class="js-cart-qty-form">
                            @csrf
                            <input type="number" name="qty" value="{{ $item['qty'] }}" min="1" max="9999" class="product-detail-modal__qty-input js-qty-restricted" inputmode="numeric" autocomplete="off" aria-label="Количество">
                        </form>
                    </td>
                    <td>{{ number_format((float) $item['price'], 0, '.', ',') }} ₽</td>
                    <td class="js-cart-line-sum">{{ number_format((float) $item['price'] * (int) $item['qty'], 0, '.', ',') }} ₽</td>
                    <td>
                        <form method="POST" action="{{ route('cart.remove', $item['id']) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-small btn-danger">Удалить</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6">Корзина пуста.</td></tr>
            @endforelse
        </tbody>
    </table>

    <h3 class="js-cart-grand-total-wrap" style="margin-top: 1rem;">Итого: <span class="js-cart-grand-total">{{ number_format((float) $total, 0, '.', ',') }}</span> ₽</h3>

    <form method="POST" action="{{ route('cart.checkout') }}" class="form-inline" style="margin-top: 1rem;">
        @csrf
        <div class="form-group">
            <label>Ваше имя</label>
            <input type="text" name="customer_name" value="{{ old('customer_name', auth()->user()?->name ?? '') }}" required>
        </div>
        <div class="form-group">
            <label for="checkout-customer-phone">Телефон</label>
            <input id="checkout-customer-phone" type="text" name="customer_phone" value="{{ old('customer_phone') }}" required maxlength="20" class="js-checkout-phone" inputmode="numeric" autocomplete="tel">
        </div>
        <button type="submit" class="btn-add">Оформить заказ</button>
    </form>
</section>
@endsection
