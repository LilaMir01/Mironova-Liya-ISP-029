@if(session('success'))
    <div class="contacts-flash-success {{ $extraClass ?? '' }}" role="status">
        <svg class="contacts-flash-success__icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true">
            <circle cx="12" cy="12" r="11" />
            <path d="M7 12l3 3 7-7" />
        </svg>
        <span>{{ session('success') }}</span>
    </div>
@endif
