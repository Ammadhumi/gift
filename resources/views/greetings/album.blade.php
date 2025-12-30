@extends('layouts.greetings', [
    'title' => 'Photo Album',
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

<div class="stage album-stage">
    <div class="album-card book-card">
        <p class="badge">Album</p>
        <h1 class="greeting-title">{{ $albumTitle }}</h1>
        <p class="greeting-message">{{ $albumSubtitle }}</p>

        @if ($photos->isEmpty())
            <p class="wish-empty">No photos were added for this greeting.</p>
        @else
            <div class="book-shell">
                <div class="album-book" data-book data-photos='@json($photos)'>
                    <!-- Book cover -->
                    <div class="book-cover" data-book-cover>
                        <span class="book-cover-title">Photo Album</span>
                        <span class="book-cover-deco"></span>
                    </div>
                    <!-- Left page -->
                    <figure class="book-page book-page--left" data-page-left-wrap>
                        <img data-page-left src="" alt="Album page">
                        <span class="page-number" data-page-num-left></span>
                    </figure>
                    <!-- Right page -->
                    <figure class="book-page book-page--right" data-page-right-wrap>
                        <img data-page-right src="" alt="Album page">
                        <span class="page-number" data-page-num-right></span>
                    </figure>
                    <span class="book-spine"></span>
                </div>
                <p class="book-hint">Swipe or use arrows to flip pages</p>
                <div class="book-controls">
                    <button class="secondary-button book-btn" type="button" data-book-prev>
                        <svg viewBox="0 0 24 24" width="20" height="20"><path d="M15 18l-6-6 6-6" fill="none" stroke="currentColor" stroke-width="2"/></svg>
                        <span>Previous</span>
                    </button>
                    <span class="book-page-indicator" data-page-indicator>1 / {{ count($photos) }}</span>
                    <button class="secondary-button book-btn" type="button" data-book-next>
                        <span>Next</span>
                        <svg viewBox="0 0 24 24" width="20" height="20"><path d="M9 18l6-6-6-6" fill="none" stroke="currentColor" stroke-width="2"/></svg>
                    </button>
                </div>
            </div>
        @endif

        <div class="album-actions">
            <a class="primary-button" href="{{ route('greetings.wish.form', $greeting) }}">Send a wish</a>
        </div>
    </div>
</div>

<div class="floor-glow"></div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const book = document.querySelector('[data-book]');
        if (!book) {
            return;
        }

        let photos = [];
        try {
            photos = JSON.parse(book.dataset.photos || '[]');
        } catch (error) {
            photos = [];
        }

        const leftWrap = book.querySelector('[data-page-left-wrap]');
        const rightWrap = book.querySelector('[data-page-right-wrap]');
        const leftImage = book.querySelector('[data-page-left]');
        const rightImage = book.querySelector('[data-page-right]');
        const prevButton = document.querySelector('[data-book-prev]');
        const nextButton = document.querySelector('[data-book-next]');
        const pageIndicator = document.querySelector('[data-page-indicator]');
        const pageNumLeft = document.querySelector('[data-page-num-left]');
        const pageNumRight = document.querySelector('[data-page-num-right]');
        const bookCover = document.querySelector('[data-book-cover]');

        if (!leftImage || !rightImage || photos.length === 0) {
            return;
        }

        const isSingle = () => window.matchMedia('(max-width: 700px)').matches;

        let index = 0;
        let isFlipping = false;

        const updateIndicator = () => {
            if (pageIndicator) {
                const single = isSingle();
                if (single) {
                    pageIndicator.textContent = `${index + 1} / ${photos.length}`;
                } else {
                    const rightIdx = Math.min(index + 2, photos.length);
                    pageIndicator.textContent = `${index + 1}-${rightIdx} / ${photos.length}`;
                }
            }
        };

        const setPages = () => {
            const single = isSingle();
            book.classList.toggle('is-single', single);

            if (bookCover) {
                bookCover.hidden = true;
            }

            if (single) {
                // Single page view for mobile
                const current = photos[index];
                leftImage.src = current;
                rightImage.src = current;
                if (leftWrap) leftWrap.style.display = 'none';
                if (rightWrap) rightWrap.style.display = 'block';
                if (pageNumRight) pageNumRight.textContent = index + 1;
            } else {
                // Double page view for desktop
                if (leftWrap) leftWrap.style.display = 'block';
                if (rightWrap) rightWrap.style.display = 'block';
                
                const left = photos[index];
                const right = photos[index + 1] || null;
                
                leftImage.src = left;
                if (pageNumLeft) pageNumLeft.textContent = index + 1;
                
                if (right) {
                    rightImage.src = right;
                    if (pageNumRight) pageNumRight.textContent = index + 2;
                    rightWrap.style.visibility = 'visible';
                } else {
                    rightWrap.style.visibility = 'hidden';
                }
            }
            
            updateIndicator();
            updateButtons();
        };

        const updateButtons = () => {
            const single = isSingle();
            const step = single ? 1 : 2;
            
            if (prevButton) {
                prevButton.disabled = index === 0;
            }
            if (nextButton) {
                nextButton.disabled = single ? index >= photos.length - 1 : index + step >= photos.length;
            }
        };

        const flip = (direction, isAutoFlip = false) => {
            if (isFlipping) return;
            
            const single = isSingle();
            const step = single ? 1 : 2;
            let newIndex = index + direction * step;
            
            // For auto-flip, loop back to start
            if (isAutoFlip && newIndex >= photos.length) {
                newIndex = 0;
            }
            
            if (newIndex < 0 || newIndex >= photos.length) return;

            isFlipping = true;
            book.classList.add('is-flipping');
            book.classList.add(direction > 0 ? 'flip-forward' : 'flip-backward');

            setTimeout(() => {
                index = newIndex;
                setPages();
                book.classList.remove('is-flipping', 'flip-forward', 'flip-backward');
                isFlipping = false;
            }, 500);
        };

        // Auto-flip timer
        let autoFlipTimer = null;
        const AUTO_FLIP_INTERVAL = 4000; // 4 seconds

        const startAutoFlip = () => {
            if (photos.length < 2) return;
            stopAutoFlip();
            autoFlipTimer = setInterval(() => {
                flip(1, true); // Pass true for auto-flip
            }, AUTO_FLIP_INTERVAL);
        };

        const stopAutoFlip = () => {
            if (autoFlipTimer) {
                clearInterval(autoFlipTimer);
                autoFlipTimer = null;
            }
        };

        // Initial setup
        setPages();
        startAutoFlip();

        if (prevButton) {
            prevButton.addEventListener('click', () => {
                flip(-1);
                startAutoFlip(); // Reset timer on manual navigation
            });
        }

        if (nextButton) {
            nextButton.addEventListener('click', () => {
                flip(1);
                startAutoFlip(); // Reset timer on manual navigation
            });
        }

        // Touch swipe support
        let touchStartX = 0;
        let touchEndX = 0;

        book.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
            stopAutoFlip(); // Pause auto-flip on touch
        }, { passive: true });

        book.addEventListener('touchend', (e) => {
            touchEndX = e.changedTouches[0].screenX;
            const diff = touchStartX - touchEndX;
            if (Math.abs(diff) > 50) {
                flip(diff > 0 ? 1 : -1);
            }
            startAutoFlip(); // Resume auto-flip after touch
        }, { passive: true });

        window.addEventListener('resize', () => {
            // Reset to first page on orientation change for consistency
            setPages();
        });
    });
</script>
@endpush
@endsection
