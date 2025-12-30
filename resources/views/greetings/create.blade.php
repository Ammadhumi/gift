@extends('layouts.greetings', [
    'title' => 'Create a QR Wish',
    'bodyClass' => 'studio theme--studio',
    'showNav' => true,
])

@section('content')
<div class="studio-shell">
    <header class="studio-header">
        <p class="studio-eyebrow">QR Wish Studio</p>
        <h1>Create a wish that becomes a scannable card.</h1>
        <p class="studio-subtitle">Choose a template, pick an occasion, and generate a QR code you can send to anyone.</p>
    </header>

    <div class="studio-grid">
        <form class="studio-form" method="POST" action="{{ route('greetings.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="field">
                <label for="recipient_name">Recipient name</label>
                <input id="recipient_name" name="recipient_name" type="text" value="{{ old('recipient_name') }}" required>
                @error('recipient_name')
                <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="field">
                <label for="occasion">Occasion</label>
                <select id="occasion" name="occasion" required>
                    <option value="" disabled {{ old('occasion') ? '' : 'selected' }}>Select an occasion</option>
                    @foreach ($occasions as $key => $label)
                        <option value="{{ $key }}" {{ old('occasion') === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @error('occasion')
                <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="field">
                <label for="template_id">Template</label>
                <select id="template_id" name="template_id">
                    <option value="" {{ old('template_id') ? '' : 'selected' }}>Default design</option>
                    @foreach ($templates as $template)
                        <option value="{{ $template->id }}" {{ (string) old('template_id') === (string) $template->id ? 'selected' : '' }}>{{ $template->name }}</option>
                    @endforeach
                </select>
                <p class="field-hint">Templates control video, fonts, and step text.</p>
                @error('template_id')
                <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="field">
                <label for="style">Style</label>
                <select id="style" name="style" required>
                    @foreach ($styles as $key => $label)
                        <option value="{{ $key }}" {{ old('style', 'spark') === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @error('style')
                <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="field">
                <label for="message">Custom message (optional)</label>
                <textarea id="message" name="message" rows="4" placeholder="Add a short note...">{{ old('message') }}</textarea>
                @error('message')
                <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="field">
                <label for="photos">Photo album (up to 12 photos)</label>
                <input id="photos" name="photos[]" type="file" multiple accept="image/*">
                <p class="field-hint">Add up to 12 photos to show on the album step.</p>
                @error('photos')
                <p class="field-error">{{ $message }}</p>
                @enderror
                @error('photos.*')
                <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <button class="primary-button" type="submit">Generate QR Code</button>
        </form>

        <aside class="studio-preview">
            <div class="preview-card">
                <p class="preview-title">What happens next</p>
                <ol class="preview-steps">
                    <li>We create your greeting and save the QR code.</li>
                    <li>The experience plays: intro, cake, wish, album, and final message.</li>
                    <li>Wishes sent back appear on your dashboard.</li>
                </ol>
                <p class="preview-note">Tip: set <code>APP_URL</code> in <code>.env</code> so the QR points to your real domain.</p>
            </div>
        </aside>
    </div>
</div>
@endsection
