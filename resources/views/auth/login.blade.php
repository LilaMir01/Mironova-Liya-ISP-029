@extends('layouts.app')

@section('title', 'Вход — Альта Дизайн')

@section('content')
<section class="auth-section">
    <div class="auth-container">
        <h1>Вход</h1>
        <p class="auth-link" style="text-align:left; margin-top: 0; margin-bottom: 1rem;">
            Директор: KmX11e@PmX.com / PaMOe12_61//Ej<br>
            Контент-менеджер: KrEat11@main.com / KreAte11#<br>
            Менеджер: MAzo22@main.com / MoKXt23#
        </p>
        @if ($errors->any())
            <ul class="auth-errors">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif
        @include('partials.flash-success', ['extraClass' => 'site-flash-success--mb'])
        <form method="POST" action="{{ route('login.post') }}" class="auth-form">
            @csrf
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
            </div>
            <div class="form-group">
                <label for="password">Пароль</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group form-group-checkbox">
                <label>
                    <input type="checkbox" name="remember"> Запомнить меня
                </label>
            </div>
            <button type="submit" class="btn-auth">Войти</button>
        </form>
        <p class="auth-link"><a href="{{ route('register') }}">Нет аккаунта? Зарегистрироваться</a></p>
    </div>
</section>
@endsection
