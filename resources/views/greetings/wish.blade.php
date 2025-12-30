@extends('layouts.greetings', [
    'title' => 'Send a Wish',
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

<div class="stage wish-stage">
    <main class="greeting-card is-revealed wish-page wish-card">
        <p class="badge">Send a wish</p>
        <h1 class="greeting-title">For {{ $greeting->recipient_name }}</h1>
        <p class="greeting-message">Pick a gift and send a short wish back.</p>

        @if ($wishSent)
            <p class="wish-sent">Your wish was sent. Thank you.</p>
        @endif

        <form class="wish-form" method="POST" action="{{ route('greetings.wish', $greeting) }}">
            @csrf

            <div class="wish-form-grid">
                <div class="wish-form-left">
                    <div class="field">
                        <label for="sender_name">Your name</label>
                        <input id="sender_name" name="sender_name" type="text" value="{{ old('sender_name') }}" required>
                        @error('sender_name')
                        <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="wish_message">Your wish</label>
                        <textarea id="wish_message" name="message" rows="3" required>{{ old('message') }}</textarea>
                        @error('message')
                        <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="wish-form-right">
                    <div class="field">
                        <p class="field-label">Choose a gift</p>
                        <div class="gift-grid">
                            @foreach ($giftOptions as $value => $label)
                                <label class="gift-option">
                                    <input type="radio" name="gift_choice" value="{{ $value }}" {{ old('gift_choice') === $value ? 'checked' : '' }} required>
                                    <span class="gift-card">
                                        <span class="gift-sticker gift-sticker--{{ $value }}">
                                            @include('greetings.partials.gift-icon', ['gift' => $value])
                                        </span>
                                        <span class="gift-label">{{ $label }}</span>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                        @error('gift_choice')
                        <p class="field-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="wish-actions">
                <button class="primary-button" type="submit">Send wish</button>
                <a class="secondary-button" href="{{ route('greetings.final', $greeting) }}">Skip for now</a>
            </div>
        </form>
    </main>
</div>

<div class="floor-glow"></div>
@endsection
