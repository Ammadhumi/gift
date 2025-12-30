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

<div class="stage">
    <main class="greeting-card is-revealed intro-card">
        <p class="badge">{{ $occasionLabel }}</p>
        <h1 class="greeting-title">{{ $introTitle }}</h1>
        <p class="greeting-message">{{ $introSubtitle }}</p>
        <a class="primary-button" href="{{ route('greetings.cake', $greeting) }}">Start</a>
    </main>
</div>

<div class="floor-glow"></div>
@endsection
