@extends('layouts.greetings', [
    'title' => 'Your Dashboard',
    'bodyClass' => 'studio theme--studio',
    'showNav' => true,
])

@section('content')
<div class="studio-shell">
    <header class="studio-header">
        <p class="studio-eyebrow">Dashboard</p>
        <h1>Your wishes and greetings</h1>
        <p class="studio-subtitle">See the wishes friends and family sent back to you.</p>
    </header>

    <div class="dashboard-grid">
        @forelse ($greetings as $greeting)
            <div class="dashboard-card">
                <div class="dashboard-card-header">
                    <div>
                        <p class="dashboard-title">{{ $greeting->recipient_name }}</p>
                        <p class="dashboard-meta">{{ ucfirst(str_replace('_', ' ', $greeting->occasion)) }} - {{ ucfirst($greeting->style) }}</p>
                    </div>
                    <a class="ghost-button" href="{{ route('greetings.intro', $greeting) }}">View</a>
                </div>

                <div class="wish-list">
                    @forelse ($greeting->wishes as $wish)
                        <div class="wish-item">
                            <p class="wish-name">{{ $wish->sender_name }}</p>
                            <p class="wish-message">{{ $wish->message }}</p>
                            @if ($wish->gift_choice)
                                <p class="wish-gift">Gift: {{ ucwords(str_replace('_', ' ', $wish->gift_choice)) }}</p>
                            @endif
                            <p class="wish-time">{{ $wish->created_at->diffForHumans() }}</p>
                        </div>
                    @empty
                        <p class="wish-empty">No wishes yet.</p>
                    @endforelse
                </div>
            </div>
        @empty
            <p class="wish-empty">You have not created any greetings yet.</p>
        @endforelse
    </div>
</div>
@endsection
