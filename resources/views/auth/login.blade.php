@extends('layouts.greetings', [
    'title' => 'Log in',
    'bodyClass' => 'studio theme--studio',
    'showNav' => true,
])

@section('content')
<div class="auth-shell">
    <div class="auth-card">
        <p class="studio-eyebrow">Welcome back</p>
        <h1>Log in to QR Wish Studio</h1>
        <p class="studio-subtitle">Create and manage your QR greetings in one place.</p>

        <form class="auth-form" method="POST" action="{{ route('login.perform') }}">
            @csrf

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

            <label class="checkbox-row">
                <input type="checkbox" name="remember">
                <span>Remember me</span>
            </label>

            <button class="primary-button" type="submit">Log in</button>
        </form>

        <p class="auth-footer">
            New here? <a href="{{ route('register') }}">Create an account</a>
        </p>
    </div>
</div>
@endsection
