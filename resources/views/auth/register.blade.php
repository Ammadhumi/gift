@extends('layouts.greetings', [
    'title' => 'Sign up',
    'bodyClass' => 'studio theme--studio',
    'showNav' => true,
])

@section('content')
<div class="auth-shell">
    <div class="auth-card">
        <p class="studio-eyebrow">Start here</p>
        <h1>Create your account</h1>
        <p class="studio-subtitle">Make QR wishes for your wife, friends, and family.</p>

        <form class="auth-form" method="POST" action="{{ route('register.perform') }}">
            @csrf

            <div class="field">
                <label for="name">Full name</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required>
                @error('name')
                <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="field">
                <label for="email">Email address</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required>
                @error('email')
                <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="field">
                <label for="password">Password</label>
                <input id="password" name="password" type="password" required>
                @error('password')
                <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="field">
                <label for="password_confirmation">Confirm password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required>
            </div>

            <button class="primary-button" type="submit">Create account</button>
        </form>

        <p class="auth-footer">
            Already have an account? <a href="{{ route('login') }}">Log in</a>
        </p>
    </div>
</div>
@endsection
