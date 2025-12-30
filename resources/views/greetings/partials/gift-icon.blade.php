@php
    $gift = $gift ?? '';
@endphp

@switch($gift)
    @case('ring')
        <svg viewBox="0 0 24 24" aria-hidden="true">
            <circle cx="12" cy="14" r="5"></circle>
            <path d="M12 3l2 2-2 2-2-2z"></path>
        </svg>
        @break
    @case('watch')
        <svg viewBox="0 0 24 24" aria-hidden="true">
            <rect x="10" y="2" width="4" height="3" rx="1"></rect>
            <rect x="10" y="19" width="4" height="3" rx="1"></rect>
            <circle cx="12" cy="12" r="5"></circle>
            <path d="M12 9v3l2 2"></path>
        </svg>
        @break
    @case('necklace')
        <svg viewBox="0 0 24 24" aria-hidden="true">
            <path d="M4 6c2.5 6.5 6.5 9.5 8 9.5s5.5-3 8-9.5"></path>
            <circle cx="12" cy="18" r="2"></circle>
        </svg>
        @break
    @case('bracelet')
        <svg viewBox="0 0 24 24" aria-hidden="true">
            <circle cx="12" cy="12" r="6"></circle>
            <circle cx="12" cy="6" r="1"></circle>
            <circle cx="18" cy="12" r="1"></circle>
            <circle cx="12" cy="18" r="1"></circle>
            <circle cx="6" cy="12" r="1"></circle>
        </svg>
        @break
    @case('perfume')
        <svg viewBox="0 0 24 24" aria-hidden="true">
            <rect x="9" y="3" width="6" height="3" rx="1"></rect>
            <rect x="7" y="6" width="10" height="12" rx="2"></rect>
            <path d="M10 11h4"></path>
        </svg>
        @break
    @case('flowers')
        <svg viewBox="0 0 24 24" aria-hidden="true">
            <circle cx="12" cy="6" r="2"></circle>
            <circle cx="12" cy="18" r="2"></circle>
            <circle cx="6" cy="12" r="2"></circle>
            <circle cx="18" cy="12" r="2"></circle>
            <circle cx="12" cy="12" r="1.5"></circle>
        </svg>
        @break
    @case('chocolate_box')
        <svg viewBox="0 0 24 24" aria-hidden="true">
            <rect x="5" y="7" width="14" height="10" rx="2"></rect>
            <path d="M5 11h14"></path>
            <path d="M12 7v10"></path>
        </svg>
        @break
    @case('teddy_bear')
        <svg viewBox="0 0 24 24" aria-hidden="true">
            <circle cx="8" cy="9" r="2"></circle>
            <circle cx="16" cy="9" r="2"></circle>
            <circle cx="12" cy="13" r="5"></circle>
            <circle cx="10" cy="13" r="1"></circle>
            <circle cx="14" cy="13" r="1"></circle>
            <path d="M10.5 15c1 .8 2 .8 3 0"></path>
        </svg>
        @break
    @default
        <svg viewBox="0 0 24 24" aria-hidden="true">
            <path d="M12 4l2.5 5 5.5.8-4 3.8 1 5.4-5-2.6-5 2.6 1-5.4-4-3.8 5.5-.8z"></path>
        </svg>
@endswitch
