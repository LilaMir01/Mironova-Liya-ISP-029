@extends('layouts.app')

@section('title', 'Кабинет менеджера — Альта Дизайн')

@section('content')
<section class="analytics-page">
    <h1 class="cabinet-main-title">Личный кабинет менеджера</h1>
    <p class="cabinet-actions-row">
        <a class="btn-add" href="{{ route('manager.orders.export') }}">Скачать отчёт по заказам</a>
        <a class="btn-add" href="{{ route('manager.feedback') }}">Обратная связь</a>
    </p>

    @include('partials.flash-success', ['extraClass' => 'site-flash-success--mt'])

    <table class="data-table" style="margin-top: 1rem;">
        <thead>
            <tr>
                <th>ID</th>
                <th>Клиент</th>
                <th>Телефон</th>
                <th>Сумма</th>
                <th>Статус</th>
                <th>Действие</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->customer_name }}</td>
                    <td>{{ $order->customer_phone }}</td>
                    <td>{{ number_format((float) $order->total, 0, '.', ',') }} ₽</td>
                    <td>{{ $order->status }}</td>
                    <td>
                        <form method="POST" action="{{ route('manager.orders.update', $order) }}">
                            @csrf
                            @method('PUT')
                            <select name="status">
                                <option value="new" {{ $order->status === 'new' ? 'selected' : '' }}>new</option>
                                <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>processing</option>
                                <option value="done" {{ $order->status === 'done' ? 'selected' : '' }}>done</option>
                            </select>
                            <button class="btn-small btn-edit" type="submit">Сохранить</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6">Заказов пока нет.</td></tr>
            @endforelse
        </tbody>
    </table>
</section>
@endsection
