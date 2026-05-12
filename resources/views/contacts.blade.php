@extends('layouts.app')



@section('title', 'Контакты — Альта Дизайн')
@section('meta_description', 'Контакты компании Альта Дизайн: адреса офисов, телефоны и форма обратной связи для консультации по фасадным материалам.')
@section('meta_keywords', 'контакты Альта Дизайн, адрес, телефон, обратная связь, консультация по фасадным материалам')



@section('content')

<section class="contacts-page-header">

    <h1>Контакты</h1>

</section>



<section class="contacts-map-wrap">

    <iframe

        class="contacts-map"

        src="https://yandex.ru/map-widget/v1/?ll=38.070000%2C56.290000&amp;z=9&amp;pt=38.136,56.315,pm2rdm~38.006,56.256,pm2rdm"

        allowfullscreen

        title="Карта офисов Альта Дизайн"

    ></iframe>

</section>



<section class="contacts-info-section">

    <div class="contacts-info-grid">

        <article class="contacts-office-card">

            <h3>Офис г. Сергиев Посад</h3>

            <p class="contacts-address">МО, г. Сергиев Посад, Ярославское шоссе д. 4В, строение 2</p>

            <p class="contacts-phone"><a href="tel:+74951234567">+7 (495) 123-45-67</a></p>

        </article>

        <article class="contacts-office-card">

            <h3>Офис г. Хотьково</h3>

            <p class="contacts-address">Московская область, Сергиево-Посадский городской округ, Хотьково, Горбуновская улица, 31</p>

            <p class="contacts-phone"><a href="tel:+74959876543">+7 (495) 987-65-43</a></p>

        </article>

    </div>

</section>



<section class="contacts-feedback-section">

    <div class="contacts-feedback-card">

        @include('partials.flash-success')

        <h2>Обратная связь</h2>

        <p class="contacts-feedback-desc">Задайте вопрос — сообщение будет передано менеджеру компании.@auth @if(auth()->user()->isCustomer()) Если вы авторизованы как клиент, ответ можно будет посмотреть в <a href="{{ route('account.index') }}">личном кабинете</a> (укажите email от аккаунта). @endif @endauth</p>

        @if($errors->any())

            <ul class="auth-errors">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        @endif



        <form method="POST" action="{{ route('contacts.store') }}" class="contacts-form">

            @csrf

            <div class="contacts-form-row">

                <div class="form-group">

                    <label for="cf-name">Имя <span class="required">*</span></label>

                    <input type="text" id="cf-name" name="name" value="{{ old('name', auth()->user()?->name ?? '') }}" placeholder="Имя" required>

                </div>

                <div class="form-group">

                    <label for="cf-email">Email <span class="required">*</span></label>

                    <input type="email" id="cf-email" name="email" value="{{ old('email', auth()->user()?->email ?? '') }}" placeholder="Email" required @auth @if(auth()->user()->isCustomer()) readonly @endif @endauth>

                </div>

            </div>

            <div class="form-group">

                <label for="cf-subject">Тема</label>

                <input type="text" id="cf-subject" name="subject" value="{{ old('subject') }}" placeholder="Тема обращения">

            </div>

            <div class="form-group">

                <label for="cf-body">Текст <span class="required">*</span></label>

                <textarea id="cf-body" name="body" rows="5" placeholder="Ваш вопрос или комментарий" required>{{ old('body') }}</textarea>

            </div>

            <button type="submit" class="btn-auth contacts-submit">Отправить сообщение</button>

        </form>

    </div>

</section>

@endsection

