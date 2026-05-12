@extends('layouts.app')

@section('title', 'Аналитика — Альта Дизайн')

@section('content')
<div class="stores-page analytics-page catalog-products-page">
    <h1 class="catalog-section-title">Аналитика</h1>

    <section class="stores-block">
        <h2>Зарегистрированные пользователи</h2>
        <p class="analytics-stat">Всего: <strong>{{ $usersCount }}</strong></p>

        <h3 class="analytics-chart-heading">Динамика регистраций по месяцам</h3>
        <p class="analytics-chart-note">Период: с марта 2026 года</p>
        <div class="analytics-reg-chart" role="img" aria-label="График количества зарегистрированных пользователей по месяцам, с марта 2026">
            @foreach($userRegistrationsByMonth as $row)
                @php
                    $h = $maxMonthlyRegistrations > 0 ? round(($row['count'] / $maxMonthlyRegistrations) * 100) : 0;
                @endphp
                <div class="analytics-reg-chart__col">
                    <div class="analytics-reg-chart__bar-wrap">
                        <span class="analytics-reg-chart__value">{{ $row['count'] }}</span>
                        <div class="analytics-reg-chart__bar" style="height: {{ $h }}%;"></div>
                    </div>
                    <span class="analytics-reg-chart__month">{{ $row['label'] }}</span>
                </div>
            @endforeach
        </div>
    </section>
</div>
@endsection
