@extends('layouts.app')

@section('title', 'Регистрация — Альта Дизайн')

@section('content')
<section class="auth-section">
    <div class="auth-container">
        <h1>Регистрация</h1>
        @if ($errors->any())
            <ul class="auth-errors">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif
        <form method="POST" action="{{ route('register') }}" class="auth-form">
            @csrf
            <div class="form-group">
                <label for="name">Имя</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required>
            </div>
            <div class="form-group">
                <label for="password">Пароль</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="password_confirmation">Подтверждение пароля</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required>
            </div>
            <button type="submit" class="btn-auth">Зарегистрироваться</button>
        </form>
        <p class="auth-link"><a href="{{ route('login') }}">Уже есть аккаунт? Войти</a></p>
    </div>
</section>
@endsection
