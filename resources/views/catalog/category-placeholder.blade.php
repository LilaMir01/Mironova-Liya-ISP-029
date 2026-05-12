@extends('layouts.app')

@section('title', 'Раздел в разработке — Альта Дизайн')
@section('meta_description', 'Раздел каталога "' . $category . '" находится в разработке. Посмотрите другие категории продукции Альта Дизайн.')
@section('meta_keywords', $category . ', каталог, фасадные материалы, Альта Дизайн')

@section('content')
<section class="page-placeholder">
    <h1>Раздел в разработке</h1>
    <p>Категория "{{ $category }}" будет добавлена в следующих итерациях.</p>
</section>
@endsection
