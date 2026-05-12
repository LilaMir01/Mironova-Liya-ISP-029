@extends('layouts.app')

@section('title', 'Личный кабинет — Альта Дизайн')

@php
    $statusLabels = [
        'new' => 'Новый',
        'processing' => 'В обработке',
        'done' => 'Выполнен',
    ];
@endphp

@section('content')
<section class="analytics-page">
    <h1 class="cabinet-main-title">Личный кабинет</h1>
    <p class="account-cabinet-intro">История заказов и ответы менеджера на ваши обращения через раздел «Контакты».</p>

    @include('partials.flash-success', ['extraClass' => 'site-flash-success--mt'])

    <div class="account-contact-block">
        <h2 class="account-section-title">Обращения и ответы</h2>
        <p class="account-contact-hint">Если вы отправляли вопрос с сайта, будучи авторизованы, ответ менеджера появится ниже.</p>

        @forelse($contactMessages as $msg)
            <article class="account-contact-card">
                <div class="account-contact-card__head">
                    <span class="account-contact-card__meta">{{ $msg->created_at->format('d.m.Y H:i') }}</span>
                    @if($msg->subject)
                        <span class="account-contact-card__subject">{{ $msg->subject }}</span>
                    @endif
                </div>
                <div class="account-contact-card__question">
                    <span class="account-contact-card__label">Ваш вопрос</span>
                    <p class="account-contact-card__body">{{ $msg->body }}</p>
                </div>
                @if($msg->manager_reply)
                    <div class="account-contact-card__reply">
                        <span class="account-contact-card__label">Ответ менеджера</span>
                        @if($msg->manager_replied_at)
                            <span class="account-contact-card__reply-date">{{ $msg->manager_replied_at->format('d.m.Y H:i') }}</span>
                        @endif
                        <p class="account-contact-card__reply-text">{{ $msg->manager_reply }}</p>
                    </div>
                @else
                    <p class="account-contact-pending">Ответ менеджера пока не готов — мы свяжемся с вами по email.</p>
                @endif
            </article>
        @empty
            <p class="account-empty">У вас пока нет обращений из личного кабинета. Написать нам можно на странице <a href="{{ route('contacts.index') }}">Контакты</a>.</p>
        @endforelse
    </div>

    <div class="account-orders-block">
        <h2 class="account-section-title">История покупок</h2>

        @forelse($orders as $order)
            <article class="account-order-card">
                <div class="account-order-card__head">
                    <span class="account-order-card__meta">Заказ №{{ $order->id }}</span>
                    <span class="account-order-card__meta">{{ $order->created_at->format('d.m.Y H:i') }}</span>
                    <span class="account-order-card__status">{{ $statusLabels[$order->status] ?? $order->status }}</span>
                    <span class="account-order-card__total">{{ number_format((float) $order->total, 0, '.', ',') }} &#8381;</span>
                </div>
                <p class="account-order-card__customer">
                    {{ $order->customer_name }}, тел. {{ $order->customer_phone }}
                </p>
                <table class="data-table account-order-items">
                    <thead>
                        <tr>
                            <th>Товар</th>
                            <th>Кол-во</th>
                            <th>Цена</th>
                            <th>Сумма</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items ?? [] as $line)
                            @php
                                $qty = (int) ($line['qty'] ?? 0);
                                $price = (float) ($line['price'] ?? 0);
                                $lineSum = $qty * $price;
                            @endphp
                            <tr>
                                <td>{{ $line['name'] ?? '—' }}</td>
                                <td>{{ $qty }}</td>
                                <td>{{ number_format($price, 0, '.', ',') }} &#8381;</td>
                                <td>{{ number_format($lineSum, 0, '.', ',') }} &#8381;</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </article>
        @empty
            <p class="account-empty">У вас пока нет заказов. Перейдите в <a href="{{ route('catalog.index') }}">каталог</a>, чтобы выбрать материалы.</p>
        @endforelse
    </div>
</section>
@endsection
