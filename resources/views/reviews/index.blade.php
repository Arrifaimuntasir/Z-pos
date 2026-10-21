@extends('layouts.admin')

@section('title', __('Rate Us'))

@section('content')
<style>
    .star-rating-box {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 99px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        transition: all 0.2s ease;
    }
    .star-btn {
        background: transparent;
        border: none;
        padding: 0;
        font-size: 2.25rem;
        line-height: 1;
        color: #cbd5e1;
        cursor: pointer;
        transition: transform 0.15s cubic-bezier(0.34, 1.56, 0.64, 1), color 0.15s ease;
        outline: none;
    }
    .star-btn:hover {
        transform: scale(1.25);
    }
    .star-btn.active,
    .star-btn.hover-active {
        color: #f59e0b;
        filter: drop-shadow(0 2px 6px rgba(245, 158, 11, 0.35));
    }
    .rating-feedback-badge {
        display: inline-block;
        padding: 0.35rem 0.85rem;
        border-radius: 99px;
        font-size: 0.85rem;
        font-weight: 700;
        background: rgba(245, 158, 11, 0.12);
        color: #d97706;
        border: 1px solid rgba(245, 158, 11, 0.25);
        transition: all 0.2s ease;
    }

    /* Dark Mode Overrides for Rate Us */
    [data-theme="dark"] .star-rating-box,
    body.dark-mode .star-rating-box {
        background: #1e293b !important;
        border-color: #334155 !important;
    }
    [data-theme="dark"] .star-btn,
    body.dark-mode .star-btn {
        color: #475569 !important;
    }
    [data-theme="dark"] .star-btn.active,
    [data-theme="dark"] .star-btn.hover-active,
    body.dark-mode .star-btn.active,
    body.dark-mode .star-btn.hover-active {
        color: #fbbf24 !important;
        filter: drop-shadow(0 2px 10px rgba(251, 191, 36, 0.45)) !important;
    }
    [data-theme="dark"] .rating-feedback-badge,
    body.dark-mode .rating-feedback-badge {
        background: rgba(245, 158, 11, 0.2) !important;
        color: #fbbf24 !important;
        border-color: rgba(245, 158, 11, 0.35) !important;
    }
    [data-theme="dark"] .rate-us-card,
    body.dark-mode .rate-us-card {
        background-color: #111827 !important;
        border-color: #1f2937 !important;
    }
    [data-theme="dark"] .rate-us-info,
    body.dark-mode .rate-us-info {
        background-color: #1e293b !important;
        border-color: #334155 !important;
        color: #94a3b8 !important;
    }
</style>

<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
    <div>
        <h4 class="fw-bold mb-0 text-dark">
            <i class="bi bi-star-fill text-warning me-2"></i>{{ __('Rate Us') }}
        </h4>
        <span class="text-muted small">{{ __('Tell us about your experience with Z-pos') }}</span>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card rate-us-card border-0 shadow-sm" style="border-radius: 16px;">
            <div class="card-body p-4 p-md-5">

                @if(session('success'))
                    <div class="alert alert-success d-flex align-items-center mb-4" role="alert" style="border-radius: 10px;">
                        <i class="bi bi-check-circle-fill fs-5 me-2"></i>
                        <div>{{ session('success') }}</div>
                    </div>
                @endif

                <form action="{{ route('reviews.store') }}" method="POST" id="reviewForm">
                    @csrf

                    <div class="text-center mb-4">
                        <label class="form-label text-muted small fw-bold text-uppercase d-block mb-3" style="letter-spacing: 0.5px;">{{ __('Your Rating') }} <span class="text-danger">*</span></label>
                        
                        {{-- Hidden real form input --}}
                        <input type="hidden" name="rating" id="selectedRating" value="{{ old('rating', $review->rating ?? 5) }}" required>

                        {{-- Interactive Star Buttons --}}
                        <div class="star-rating-box mb-2" id="starRatingContainer">
                            @for($i = 1; $i <= 5; $i++)
                                <button type="button" class="star-btn {{ old('rating', $review->rating ?? 5) >= $i ? 'active' : '' }}" data-value="{{ $i }}" aria-label="Rate {{ $i }} stars">
                                    <i class="bi bi-star-fill"></i>
                                </button>
                            @endfor
                        </div>

                        <div class="mt-2">
                            <span class="rating-feedback-badge" id="ratingFeedbackText">
                                @php
                                    $cur = (int)old('rating', $review->rating ?? 5);
                                    $labels = [
                                        1 => __('1 - Very Poor'),
                                        2 => __('2 - Poor'),
                                        3 => __('3 - Average'),
                                        4 => __('4 - Good'),
                                        5 => __('5 - Excellent')
                                    ];
                                @endphp
                                {{ $labels[$cur] ?? __('5 - Excellent') }}
                            </span>
                        </div>

                        @error('rating')
                            <div class="text-danger small mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-muted small fw-medium">{{ __('Your Review') }} <span class="text-danger">*</span></label>
                        <textarea name="review" class="form-control @error('review') is-invalid @enderror" rows="5" maxlength="1000" placeholder="{{ __('Share your experience using Z-pos...') }}" required>{{ old('review', $review->quote ?? '') }}</textarea>
                        @error('review')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="rate-us-info p-3 rounded-3 mb-4 d-flex align-items-center gap-2 border bg-light text-muted small">
                        <i class="bi bi-info-circle-fill text-primary fs-5 flex-shrink-0"></i>
                        <span>{{ __('Only 5-star reviews with written feedback are featured on our homepage.') }}</span>
                    </div>

                    <button type="submit" class="btn btn-success w-100 py-2 fw-semibold" style="border-radius: 8px;">
                        <i class="bi bi-send-fill me-1"></i>
                        {{ $review ? __('Update Review') : __('Submit Review') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const ratingInput = document.getElementById('selectedRating');
    const container = document.getElementById('starRatingContainer');
    const stars = container.querySelectorAll('.star-btn');
    const feedbackText = document.getElementById('ratingFeedbackText');

    const feedbackMap = {
        1: @json(__('1 - Very Poor')),
        2: @json(__('2 - Poor')),
        3: @json(__('3 - Average')),
        4: @json(__('4 - Good')),
        5: @json(__('5 - Excellent'))
    };

    function renderStars(rating, isHover = false) {
        stars.forEach(btn => {
            const val = parseInt(btn.dataset.value, 10);
            if (isHover) {
                if (val <= rating) {
                    btn.classList.add('hover-active');
                } else {
                    btn.classList.remove('hover-active');
                }
            } else {
                if (val <= rating) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
                btn.classList.remove('hover-active');
            }
        });

        if (feedbackMap[rating]) {
            feedbackText.textContent = feedbackMap[rating];
        }
    }

    // Initialize display
    const currentVal = parseInt(ratingInput.value, 10) || 5;
    renderStars(currentVal, false);

    // Click handler for stars
    stars.forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const val = parseInt(this.dataset.value, 10);
            ratingInput.value = val;
            renderStars(val, false);
        });

        btn.addEventListener('mouseenter', function () {
            const val = parseInt(this.dataset.value, 10);
            renderStars(val, true);
        });
    });

    // Reset when mouse leaves the star container
    container.addEventListener('mouseleave', function () {
        const val = parseInt(ratingInput.value, 10) || 5;
        renderStars(val, false);
    });
});
</script>
@endsection
