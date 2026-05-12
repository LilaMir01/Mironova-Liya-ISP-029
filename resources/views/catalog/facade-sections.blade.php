@extends('layouts.app')

@section('title', 'Фасадные материалы — Альта Дизайн')

@section('content')
<section class="catalog-page">
    <h1>Фасадные материалы</h1>
    <div class="section-grid">
        @foreach($sections as $section)
            <a href="{{ route('catalog.section', ['category' => 'facade', 'sectionSlug' => $section->slug]) }}" class="section-card">
                <img src="{{ $section->image_path ?: 'https://via.placeholder.com/420x240?text=Alta+Design' }}" alt="{{ $section->name }}">
                <span>{{ $section->name }}</span>
            </a>
        @endforeach
    </div>
</section>
@endsection
