@extends('layouts.app')

@section('title', 'Обратная связь — Альта Дизайн')

@section('content')
<section class="analytics-page">
    <h1 class="cabinet-main-title">Обращения с сайта (Контакты)</h1>

    <p class="cabinet-actions-row">
        <a href="{{ route('manager.orders') }}" class="btn-add">К заказам</a>
    </p>

    @include('partials.flash-success', ['extraClass' => 'site-flash-success--mt'])

    @if($errors->any())
        <ul class="auth-errors">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <div class="manager-feedback-table-wrap">
        <table class="data-table manager-feedback-table">
            <thead>
                <tr>
                    <th>Дата</th>
                    <th>Имя</th>
                    <th>Email</th>
                    <th>Тема</th>
                    <th>Сообщение</th>
                    <th>Ответ менеджера</th>
                </tr>
            </thead>
            <tbody>
                @forelse($messages as $msg)
                    <tr>
                        <td>{{ $msg->created_at->format('d.m.Y H:i') }}</td>
                        <td>{{ $msg->name }}</td>
                        <td><a href="mailto:{{ $msg->email }}">{{ $msg->email }}</a></td>
                        <td>{{ $msg->subject ?: '—' }}</td>
                        <td class="manager-feedback-body-cell">{{ $msg->body }}</td>
                        <td class="manager-feedback-reply-cell">
                            @if($msg->manager_replied_at)
                                <p class="manager-feedback-replied-at">Отправлено: {{ $msg->manager_replied_at->format('d.m.Y H:i') }}</p>
                            @endif
                            <form method="POST" action="{{ route('manager.feedback.reply', $msg) }}" class="manager-reply-form">
                                @csrf
                                @method('PUT')
                                <label class="sr-only" for="manager-reply-{{ $msg->id }}">Текст ответа клиенту</label>
                                <textarea id="manager-reply-{{ $msg->id }}" name="manager_reply" rows="5" class="manager-reply-textarea" required placeholder="Текст ответа увидит клиент в личном кабинете (если обращение с аккаунта).">{{ old('manager_reply', $msg->manager_reply) }}</textarea>
                                <button type="submit" class="btn-small btn-edit manager-reply-submit">Сохранить ответ</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6">Пока нет сообщений.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection
