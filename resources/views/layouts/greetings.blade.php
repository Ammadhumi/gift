<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'QR Wish Studio' }}</title>
    <link rel="stylesheet" href="{{ asset('css/greetings.css') }}">
    @if (!empty($fontUrl))
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="stylesheet" href="{{ $fontUrl }}">
    @endif
</head>
<body class="{{ $bodyClass ?? '' }}" style="{{ $bodyStyle ?? '' }}">
    @if (!empty($showNav))
        <nav class="studio-nav">
            <a class="nav-brand" href="{{ route('greetings.create') }}">QR Wish</a>
            <div class="nav-actions">
                @auth
                    <a class="secondary-button nav-button" href="{{ route('dashboard') }}">Dashboard</a>
                    <a class="secondary-button nav-button" href="{{ route('templates.index') }}">Templates</a>
                    @role('admin')
                        <a class="secondary-button nav-button" href="{{ route('admin.users.index') }}">Admin</a>
                    @endrole
                    <span class="nav-user">Hi {{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="ghost-button nav-button" type="submit">Log out</button>
                    </form>
                @else
                    <a class="ghost-button nav-button" href="{{ route('login') }}">Log in</a>
                    <a class="primary-button nav-button" href="{{ route('register') }}">Sign up</a>
                @endauth
            </div>
        </nav>
    @endif

    @if (!empty($videoUrl))
        <div class="video-wrap" aria-hidden="true">
            <video class="video-bg" autoplay muted loop playsinline>
                <source src="{{ $videoUrl }}" type="video/mp4">
            </video>
        </div>
    @endif

    @if (!empty($audioTracks))
        <audio id="bg-audio" preload="auto"></audio>
        <button class="secondary-button audio-toggle" type="button" data-audio-toggle>Play music</button>
    @endif

    <div class="page">
        @yield('content')
    </div>
    @if (!empty($audioTracks))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const tracks = @json($audioTracks);
                const audio = document.getElementById('bg-audio');
                const toggle = document.querySelector('[data-audio-toggle]');

                if (!audio || !tracks || tracks.length === 0) {
                    return;
                }

                const storageKey = 'greeting_audio_state';
                audio.volume = 0.6;

                const readState = () => {
                    try {
                        return JSON.parse(localStorage.getItem(storageKey) || '{}');
                    } catch (error) {
                        return {};
                    }
                };

                const saved = readState();
                let currentIndex = typeof saved.index === 'number'
                    ? Math.min(saved.index, tracks.length - 1)
                    : 0;
                let resumeTime = typeof saved.time === 'number' ? saved.time : 0;
                let shouldAutoplay = saved.enabled !== false;

                const updateToggle = () => {
                    if (!toggle) {
                        return;
                    }
                    toggle.textContent = audio.paused ? 'Play music' : 'Pause music';
                };

                const persist = (enabled) => {
                    localStorage.setItem(storageKey, JSON.stringify({
                        index: currentIndex,
                        time: audio.currentTime || 0,
                        enabled: enabled,
                    }));
                };

                const play = () => {
                    audio.play()
                        .then(() => {
                            shouldAutoplay = true;
                            updateToggle();
                            persist(true);
                        })
                        .catch(() => {
                            updateToggle();
                        });
                };

                const pause = () => {
                    audio.pause();
                    shouldAutoplay = false;
                    persist(false);
                    updateToggle();
                };

                const setTrack = (index, time, autoplay) => {
                    currentIndex = index;
                    audio.src = tracks[currentIndex];
                    audio.load();

                    const onMeta = () => {
                        audio.removeEventListener('loadedmetadata', onMeta);
                        if (time > 0 && !Number.isNaN(audio.duration) && audio.duration > 0) {
                            audio.currentTime = Math.min(time, audio.duration - 0.25);
                        }
                        if (autoplay) {
                            play();
                        }
                    };

                    audio.addEventListener('loadedmetadata', onMeta);

                    if (time <= 0 && autoplay) {
                        play();
                    }
                };

                setTrack(currentIndex, resumeTime, shouldAutoplay);
                updateToggle();

                audio.addEventListener('ended', () => {
                    setTrack((currentIndex + 1) % tracks.length, 0, shouldAutoplay);
                });

                if (toggle) {
                    toggle.addEventListener('click', () => {
                        if (audio.paused) {
                            play();
                        } else {
                            pause();
                        }
                    });
                }

                const unlock = () => {
                    if (audio.paused && shouldAutoplay) {
                        play();
                    }
                    document.removeEventListener('click', unlock);
                    document.removeEventListener('touchstart', unlock);
                };

                document.addEventListener('click', unlock);
                document.addEventListener('touchstart', unlock);

                let lastSave = 0;
                audio.addEventListener('timeupdate', () => {
                    if (audio.paused) {
                        return;
                    }
                    const now = Date.now();
                    if (now - lastSave > 2000) {
                        lastSave = now;
                        persist(true);
                    }
                });

                const saveOnHide = () => {
                    if (!audio.paused) {
                        persist(true);
                    }
                };

                window.addEventListener('pagehide', saveOnHide);
                document.addEventListener('visibilitychange', () => {
                    if (document.visibilityState === 'hidden') {
                        saveOnHide();
                    }
                });
            });
        </script>
    @endif

    @stack('scripts')
</body>
</html>
