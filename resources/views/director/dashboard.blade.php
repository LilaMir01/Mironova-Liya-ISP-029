@extends('layouts.app')

@section('title', 'Кабинет директора — Альта Дизайн')

@section('content')
<section class="analytics-page">
    <h1 class="cabinet-main-title">Личный кабинет директора</h1>

    <h2 class="director-staff-heading">Управление email и паролями сотрудников</h2>
    @include('partials.flash-success', ['extraClass' => 'site-flash-success--mb'])

    @if($contentCreator)
        <form
            method="POST"
            action="{{ route('director.users.update', $contentCreator) }}"
            class="form-inline js-director-credentials-form"
            data-staff-role-label="контент-менеджера"
        >
            @csrf
            @method('PUT')
            <div class="form-group">
                <label>Контент-менеджер</label>
                <input type="email" name="email" value="{{ $contentCreator->email }}" required autocomplete="email">
            </div>
            <div class="form-group">
                <label>Новый пароль</label>
                <input type="password" name="password" required autocomplete="new-password" minlength="8">
            </div>
            <button type="submit" class="btn-add">Сохранить</button>
        </form>
    @endif

    @if($managerUser)
        <form
            method="POST"
            action="{{ route('director.users.update', $managerUser) }}"
            class="form-inline js-director-credentials-form"
            data-staff-role-label="менеджера"
        >
            @csrf
            @method('PUT')
            <div class="form-group">
                <label>Менеджер</label>
                <input type="email" name="email" value="{{ $managerUser->email }}" required autocomplete="email">
            </div>
            <div class="form-group">
                <label>Новый пароль</label>
                <input type="password" name="password" required autocomplete="new-password" minlength="8">
            </div>
            <button type="submit" class="btn-add">Сохранить</button>
        </form>
    @endif

    @if(!$contentCreator && !$managerUser)
        <p class="analytics-stat">Учётные записи контент-менеджера и менеджера не найдены в базе.</p>
    @endif
</section>
@endsection
