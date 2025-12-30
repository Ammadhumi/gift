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

<!-- Confetti container for celebration -->
<div class="confetti-container" data-confetti hidden aria-hidden="true">
    @for ($i = 1; $i <= 50; $i++)
    <span class="confetti confetti--{{ $i % 8 + 1 }}"></span>
    @endfor
</div>

<!-- Balloon rain for celebration -->
<div class="balloon-rain gift-balloons" data-gift-balloons hidden aria-hidden="true">
    <span class="balloon balloon--1"></span>
    <span class="balloon balloon--2"></span>
    <span class="balloon balloon--3"></span>
    <span class="balloon balloon--4"></span>
    <span class="balloon balloon--5"></span>
    <span class="balloon balloon--6"></span>
    <span class="balloon balloon--7"></span>
    <span class="balloon balloon--8"></span>
    <span class="balloon balloon--9"></span>
    <span class="balloon balloon--10"></span>
    <span class="balloon balloon--11"></span>
    <span class="balloon balloon--12"></span>
</div>

<div class="stage">
    <main class="greeting-card is-revealed final-card">
        <p class="badge">{{ $occasionLabel }}</p>
        <h1 class="greeting-title">{{ $finalTitle }}</h1>
        <p class="greeting-message">{{ $finalSubtitle }}</p>

        <div class="gift-stage" data-gift-stage>
            <div class="gift-box" data-gift-box>
                <span class="gift-lid"></span>
                <span class="gift-base"></span>
                <span class="gift-ribbon"></span>
            </div>
            <button class="primary-button" type="button" data-open-gift>Open the gift</button>
        </div>

        <p class="final-message" data-final-message hidden>{{ $message }}</p>

        <div class="album-actions">
            <a class="secondary-button" href="{{ route('greetings.intro', $greeting) }}">Replay</a>
        </div>
    </main>
</div>

<!-- Gift Popup Modal -->
<div class="gift-popup-overlay" data-gift-popup hidden>
    <div class="gift-popup">
        <div class="gift-popup-sparkle"></div>
        <div class="gift-popup-content">
            <h2 class="gift-popup-title">🎉 Congratulations! 🎉</h2>
            
            @php
                $giftImages = [
                    'ring' => 'ring.jpeg',
                    'watch' => 'watch.jpeg',
                    'necklace' => 'necklace.jpg',
                    'bracelet' => 'bracelet.jpeg',
                    'perfume' => 'perfume.jpeg',
                    'flowers' => 'flowers.jpeg',
                    'chocolate_box' => 'chocolatebox.jpeg',
                    'teddy_bear' => 'teddy_bear.jpeg',
                ];
                $giftImage = $giftImages[$giftChoice] ?? null;
            @endphp
            
            @if($giftImage)
                <div class="gift-popup-image-wrap">
                    <img class="gift-popup-image" src="{{ asset($giftImage) }}" alt="{{ $giftLabel }}">
                </div>
            @else
                <div class="gift-popup-icon gift-sticker--{{ $giftChoice }}">
                    @include('greetings.partials.gift-icon', ['gift' => $giftChoice])
                </div>
            @endif
            
            <p class="gift-popup-label">You received a</p>
            <p class="gift-popup-name">{{ $giftLabel }}</p>
            <p class="gift-popup-cheer" data-cheer-text>{{ $occasionLabel }}! Enjoy your special gift!</p>
            <button class="primary-button gift-popup-collect" type="button" data-collect-gift>Collect Gift</button>
        </div>
    </div>
</div>

<!-- Farewell Message -->
<div class="farewell-overlay" data-farewell-overlay hidden>
    <div class="farewell-content">
        <span class="farewell-wave">👋</span>
        <h2 class="farewell-title">Thank You!</h2>
        <p class="farewell-text">Have a wonderful day!</p>
        <a class="secondary-button" href="{{ route('greetings.intro', $greeting) }}">View Again</a>
    </div>
</div>

<div class="floor-glow"></div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const stage = document.querySelector('[data-gift-stage]');
        const openButton = document.querySelector('[data-open-gift]');
        const giftPopup = document.querySelector('[data-gift-popup]');
        const confetti = document.querySelector('[data-confetti]');
        const giftBalloons = document.querySelector('[data-gift-balloons]');
        const collectButton = document.querySelector('[data-collect-gift]');
        const farewellOverlay = document.querySelector('[data-farewell-overlay]');
        const finalMessage = document.querySelector('[data-final-message]');
        const giftBox = document.querySelector('[data-gift-box]');

        if (!stage || !openButton) {
            return;
        }

        // Open gift and show popup
        openButton.addEventListener('click', () => {
            stage.classList.add('is-open');
            openButton.disabled = true;
            openButton.hidden = true;

            // Show confetti and balloons
            if (confetti) {
                confetti.hidden = false;
            }
            if (giftBalloons) {
                giftBalloons.hidden = false;
            }

            // Show gift popup after box animation
            setTimeout(() => {
                if (giftPopup) {
                    giftPopup.hidden = false;
                    setTimeout(() => {
                        giftPopup.classList.add('is-visible');
                    }, 50);
                }
            }, 600);

            if (finalMessage) {
                finalMessage.hidden = false;
            }
        });

        // Collect gift
        if (collectButton) {
            collectButton.addEventListener('click', () => {
                if (giftPopup) {
                    giftPopup.classList.remove('is-visible');
                    setTimeout(() => {
                        giftPopup.hidden = true;
                    }, 400);
                }

                if (confetti) {
                    confetti.hidden = true;
                }

                if (giftBalloons) {
                    giftBalloons.hidden = true;
                }

                if (giftBox) {
                    giftBox.hidden = true;
                }

                // Show farewell
                setTimeout(() => {
                    if (farewellOverlay) {
                        farewellOverlay.hidden = false;
                        setTimeout(() => {
                            farewellOverlay.classList.add('is-visible');
                        }, 50);
                    }
                }, 300);
            });
        }
    });
</script>
@endpush
@endsection
