@extends('layouts.greetings', [
    'title' => $occasionLabel . ' for ' . $greeting->recipient_name,
    'bodyClass' => 'theme theme--' . $theme,
    'fontUrl' => $fontUrl,
    'bodyStyle' => $bodyStyle,
])

@section('content')
<div class="ambient">
    <span class="orb orb--1"></span>
    <span class="orb orb--2"></span>
    <span class="orb orb--3"></span>
    <span class="spark spark--1"></span>
    <span class="spark spark--2"></span>
    <span class="spark spark--3"></span>
</div>

<div class="balloon-rain" aria-hidden="true">
    <span class="balloon balloon--1"></span>
    <span class="balloon balloon--2"></span>
    <span class="balloon balloon--3"></span>
    <span class="balloon balloon--4"></span>
    <span class="balloon balloon--5"></span>
    <span class="balloon balloon--6"></span>
    <span class="balloon balloon--7"></span>
    <span class="balloon balloon--8"></span>
    <span class="balloon balloon--9"></span>
</div>

<div class="stage">
    <div class="cake-stage" data-cake-stage>
        <p class="badge">{{ $occasionLabel }}</p>
        <h1 class="greeting-title">{{ $cakeTitle }}</h1>
        <p class="greeting-message">{{ $cakeSubtitle }}</p>

        <div class="cake">
            <span class="cake-half cake-half--left"></span>
            <span class="cake-half cake-half--right"></span>
            <span class="cake-icing"></span>
            <div class="cake-candles">
                <span class="cake-candle candle--1"></span>
                <span class="cake-candle candle--2"></span>
                <span class="cake-candle candle--3"></span>
                <span class="cake-candle candle--4"></span>
                <span class="cake-candle candle--5"></span>
                <span class="cake-candle candle--6"></span>
                <span class="cake-candle candle--7"></span>
            </div>
            <span class="cake-knife"></span>
        </div>

        <button class="primary-button" type="button" data-cut-button>Cut the cake</button>
        <p class="cut-next" data-cut-next hidden>Redirecting to the album...</p>
        <a class="secondary-button cut-link" data-cut-link href="{{ route('greetings.album', $greeting) }}" hidden>Go to album</a>
    </div>
</div>

<div class="floor-glow"></div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.body.classList.add('is-ready');

        const cutButton = document.querySelector('[data-cut-button]');
        const cutNext = document.querySelector('[data-cut-next]');
        const cutLink = document.querySelector('[data-cut-link]');
        const albumUrl = "{{ route('greetings.album', $greeting) }}";

        if (cutButton) {
            cutButton.addEventListener('click', () => {
                document.body.classList.add('cake-cut');
                cutButton.disabled = true;

                if (cutNext) {
                    cutNext.hidden = false;
                }
                if (cutLink) {
                    cutLink.hidden = false;
                }

                setTimeout(() => {
                    window.location.href = albumUrl;
                }, 2200);
            });
        }
    });
</script>
@endpush
@endsection
