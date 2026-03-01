@extends('layouts.app')

@section('title', 'Аналитика — Альта Дизайн')

@section('content')
<div class="stores-page analytics-page">

    <section class="stores-block">
        <h2>Зарегистрированные пользователи</h2>
        <p class="analytics-stat">Количество: <strong>{{ $usersCount }}</strong></p>
    </section>

    <section class="stores-block">
        <h2>Продажи</h2>

        <form method="GET" action="{{ route('analytics.index') }}" class="analytics-filter">
            <div class="form-group">
                <label>Период</label>
                <select name="period_type" id="period-type" class="period-select">
                    <option value="all" {{ $periodType === 'all' ? 'selected' : '' }}>За весь период</option>
                    <option value="day" {{ $periodType === 'day' ? 'selected' : '' }}>День</option>
                    <option value="month" {{ $periodType === 'month' ? 'selected' : '' }}>Месяц</option>
                    <option value="year" {{ $periodType === 'year' ? 'selected' : '' }}>Год</option>
                </select>
            </div>
            <div class="form-group period-day" style="display: {{ $periodType === 'day' ? 'block' : 'none' }};">
                <label>День</label>
                <input type="number" name="day" min="1" max="31" value="{{ $day }}">
            </div>
            <div class="form-group period-month" style="display: {{ in_array($periodType, ['day', 'month']) ? 'block' : 'none' }};">
                <label>Месяц</label>
                <select name="month">
                    @php $months = [1=>'Январь',2=>'Февраль',3=>'Март',4=>'Апрель',5=>'Май',6=>'Июнь',7=>'Июль',8=>'Август',9=>'Сентябрь',10=>'Октябрь',11=>'Ноябрь',12=>'Декабрь']; @endphp
                    @foreach ($months as $m => $name)
                        <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group period-year" style="display: {{ in_array($periodType, ['day', 'month', 'year']) ? 'block' : 'none' }};">
                <label>Год</label>
                <select name="year">
                    @for ($y = now()->year; $y >= now()->year - 5; $y--)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <button type="submit" class="btn-add">Применить</button>
        </form>

        <p class="analytics-stat">Количество продаж: <strong>{{ $salesCount }}</strong></p>
        <p class="analytics-stat">Суммарное количество товаров: <strong>{{ $totalQuantity }}</strong></p>

        <table class="data-table catalogue-table">
            <thead>
                <tr>
                    <th>Дата</th>
                    <th>Материал</th>
                    <th>Склад</th>
                    <th>Количество</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sales as $sale)
                    <tr>
                        <td>{{ $sale->sale_date->format('d.m.Y') }}</td>
                        <td>{{ $sale->material->display_name ?? '—' }}</td>
                        <td>{{ $sale->warehouse->name ?? '—' }}</td>
                        <td>{{ $sale->quantity }}</td>
                    </tr>
                @empty
                    <tr class="empty-row"><td colspan="4">Нет продаж за выбранный период.</td></tr>
                @endforelse
            </tbody>
        </table>
    </section>
</div>

<script>
(function() {
    const periodType = document.getElementById('period-type');
    const dayBlock = document.querySelector('.period-day');
    const monthBlock = document.querySelector('.period-month');
    const yearBlock = document.querySelector('.period-year');

    function toggle() {
        const v = periodType.value;
        dayBlock.style.display = v === 'day' ? 'block' : 'none';
        monthBlock.style.display = (v === 'day' || v === 'month') ? 'block' : 'none';
        yearBlock.style.display = (v === 'day' || v === 'month' || v === 'year') ? 'block' : 'none';
    }
    periodType.addEventListener('change', toggle);
})();
</script>
@endsection
