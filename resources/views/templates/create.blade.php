@extends('layouts.greetings', [
    'title' => 'Create Template',
    'bodyClass' => 'studio theme--studio',
    'showNav' => true,
])

@section('content')
<div class="studio-shell">
    <header class="studio-header">
        <p class="studio-eyebrow">Template Studio</p>
        <h1>Create a new template</h1>
        <p class="studio-subtitle">Use {name} and {occasion} to personalize the text automatically.</p>
    </header>

    <form class="admin-form" method="POST" action="{{ route('templates.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="field">
            <label for="name">Template name</label>
            <input id="name" name="name" type="text" value="{{ old('name') }}" required>
            @error('name')
            <p class="field-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="admin-grid">
            <div class="field">
                <label for="theme">Theme</label>
                <select id="theme" name="theme" required>
                    @foreach ($themes as $key => $label)
                        <option value="{{ $key }}" {{ old('theme', 'spark') === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @error('theme')
                <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="field">
                <label for="font_family">Font</label>
                <select id="font_family" name="font_family" required>
                    @foreach ($fonts as $fontName => $fontUrl)
                        <option value="{{ $fontName }}" {{ old('font_family', 'Playfair Display') === $fontName ? 'selected' : '' }}>{{ $fontName }}</option>
                    @endforeach
                </select>
                @error('font_family')
                <p class="field-error">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="admin-grid">
            <div class="field">
                <label for="intro_title">Intro title</label>
                <input id="intro_title" name="intro_title" type="text" value="{{ old('intro_title') }}" placeholder="Welcome {name}">
                @error('intro_title')
                <p class="field-error">{{ $message }}</p>
                @enderror
            </div>
            <div class="field">
                <label for="intro_subtitle">Intro subtitle</label>
                <input id="intro_subtitle" name="intro_subtitle" type="text" value="{{ old('intro_subtitle') }}" placeholder="Today is {occasion}">
                @error('intro_subtitle')
                <p class="field-error">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="admin-grid">
            <div class="field">
                <label for="cake_title">Cake title</label>
                <input id="cake_title" name="cake_title" type="text" value="{{ old('cake_title') }}" placeholder="Lets celebrate">
                @error('cake_title')
                <p class="field-error">{{ $message }}</p>
                @enderror
            </div>
            <div class="field">
                <label for="cake_subtitle">Cake subtitle</label>
                <input id="cake_subtitle" name="cake_subtitle" type="text" value="{{ old('cake_subtitle') }}" placeholder="Make a wish for {name}">
                @error('cake_subtitle')
                <p class="field-error">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="admin-grid">
            <div class="field">
                <label for="album_title">Album title</label>
                <input id="album_title" name="album_title" type="text" value="{{ old('album_title') }}" placeholder="Memories together">
                @error('album_title')
                <p class="field-error">{{ $message }}</p>
                @enderror
            </div>
            <div class="field">
                <label for="album_subtitle">Album subtitle</label>
                <input id="album_subtitle" name="album_subtitle" type="text" value="{{ old('album_subtitle') }}" placeholder="A few highlights">
                @error('album_subtitle')
                <p class="field-error">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="admin-grid">
            <div class="field">
                <label for="final_title">Final title</label>
                <input id="final_title" name="final_title" type="text" value="{{ old('final_title') }}" placeholder="Happy {occasion}">
                @error('final_title')
                <p class="field-error">{{ $message }}</p>
                @enderror
            </div>
            <div class="field">
                <label for="final_subtitle">Final subtitle</label>
                <input id="final_subtitle" name="final_subtitle" type="text" value="{{ old('final_subtitle') }}" placeholder="With love for {name}">
                @error('final_subtitle')
                <p class="field-error">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="field">
            <label for="video">Background video (MP4, max 10 MB)</label>
            <input id="video" name="video" type="file" accept="video/mp4">
            @error('video')
            <p class="field-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="admin-actions">
            <button class="primary-button" type="submit">Create template</button>
            <a class="secondary-button" href="{{ route('templates.index') }}">Cancel</a>
        </div>
    </form>
</div>
@endsection
