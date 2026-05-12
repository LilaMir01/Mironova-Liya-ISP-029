@php
    $__footerFacade = \App\Http\Controllers\CatalogController::facadeSectionNames();
    $__footerMid = (int) ceil(count($__footerFacade) / 2);
    $__footerFacadeA = array_slice($__footerFacade, 0, $__footerMid);
    $__footerFacadeB = array_slice($__footerFacade, $__footerMid);
@endphp
<footer class="site-footer" aria-label="Подвал сайта">
    <div class="site-footer__main">
        <div class="site-footer__grid">
            <div class="site-footer__block site-footer__block--nav">
                <a href="{{ route('contacts.index') }}" class="site-footer__heading-link">Контакты</a>
                @auth
                    @if(auth()->user()->isManager() || auth()->user()->isDirector())
                        <a href="{{ route('analytics.index') }}" class="site-footer__heading-link">Аналитика</a>
                    @endif
                @endauth
                <a href="{{ url('/#about') }}" class="site-footer__heading-link">О нас</a>
            </div>
            <div class="site-footer__block site-footer__block--products">
                <p class="site-footer__block-title">Продукция</p>
                <div class="site-footer__products">
                    <div>
                        <ul class="site-footer__list">
                            @foreach($__footerFacadeA as $name)
                                <li><a href="{{ route('catalog.section', ['category' => 'facade', 'sectionSlug' => \Illuminate\Support\Str::slug($name)]) }}">{{ $name }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                    <div>
                        <ul class="site-footer__list">
                            @foreach($__footerFacadeB as $name)
                                <li><a href="{{ route('catalog.section', ['category' => 'facade', 'sectionSlug' => \Illuminate\Support\Str::slug($name)]) }}">{{ $name }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                    <div>
                        <ul class="site-footer__list">
                            <li><a href="{{ route('catalog.category', 'floor') }}">Напольные покрытия</a></li>
                            <li><a href="{{ route('catalog.category', 'terrace') }}">Террасная доска</a></li>
                            <li><a href="{{ route('catalog.category', 'drainage') }}">Водосточные системы</a></li>
                            <li><a href="{{ route('catalog.category', 'ventilation') }}">Вентиляция</a></li>
                            <li><a href="{{ route('catalog.category', 'insulation') }}">Утеплитель и изоляция</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="site-footer__legal">
        © 2026, Альта Дизайн все права защищены
    </div>
</footer>
