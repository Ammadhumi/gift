@extends('layouts.greetings', [
    'title' => 'Share Your QR Wish',
    'bodyClass' => 'studio theme--studio',
    'showNav' => true,
])

@section('content')
<div class="studio-shell">
    <header class="studio-header">
        <p class="studio-eyebrow">QR ready</p>
        <h1>Your QR wish is ready to share.</h1>
        <p class="studio-subtitle">Send the QR image or the link below. The recipient will see the animated card.</p>
    </header>

    <div class="share-grid">
        <div class="share-card">
            <div class="share-meta">
                <p class="meta-label">Occasion</p>
                <p class="meta-value">{{ $occasionLabel }}</p>
                <p class="meta-label">Style</p>
                <p class="meta-value">{{ $styleLabel }}</p>
            </div>

            <div class="qr-frame">
                @if ($qrUrl)
                    <img src="{{ $qrUrl }}" alt="QR code for {{ $greeting->recipient_name }}">
                @else
                    <p class="field-error">QR code not available yet. Please refresh.</p>
                @endif
            </div>

            <div class="share-actions">
                @if ($qrUrl)
                    <a class="secondary-button" href="{{ $qrUrl }}" download>Download QR</a>
                @endif
                <a class="primary-button" href="{{ $shareUrl }}">Preview Experience</a>
            </div>

            <div class="share-link">
                <label for="share-link">Share link</label>
                <div class="share-link-row">
                    <input id="share-link" type="text" value="{{ $shareUrl }}" readonly>
                    <button class="ghost-button" type="button" data-copy-button>Copy</button>
                </div>
            </div>
        </div>

        <aside class="studio-preview">
            <div class="preview-card">
                <p class="preview-title">Send it anywhere</p>
                <p class="preview-note">Print the QR on a card, share in chat, or add it to a gift tag.</p>
                <p class="preview-note">Scan with any phone camera to open the animated wish page.</p>
            </div>
        </aside>
    </div>
</div>

@push('scripts')
<script>
    const copyButton = document.querySelector('[data-copy-button]');
    const shareInput = document.getElementById('share-link');

    if (copyButton && shareInput) {
        copyButton.addEventListener('click', () => {
            const finish = (copied) => {
                copyButton.textContent = copied ? 'Copied' : 'Copy';
                setTimeout(() => {
                    copyButton.textContent = 'Copy';
                }, 1200);
            };

            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(shareInput.value)
                    .then(() => finish(true))
                    .catch(() => finish(false));
            } else {
                shareInput.select();
                shareInput.setSelectionRange(0, shareInput.value.length);
                const copied = document.execCommand('copy');
                finish(copied);
            }
        });
    }
</script>
@endpush
@endsection
