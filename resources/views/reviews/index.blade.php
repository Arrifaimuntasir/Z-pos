@extends('layouts.admin')

@section('title', 'Rate Us')

@section('content')
<style>
    .star-rating {
        display: inline-flex;
        flex-direction: row-reverse;
        gap: 0.25rem;
    }
    .star-rating input {
        display: none;
    }
    .star-rating label {
        font-size: 2.5rem;
        color: #e2e8f0;
        cursor: pointer;
        transition: color 0.15s ease;
    }
    .star-rating input:checked ~ label,
    .star-rating label:hover,
    .star-rating label:hover ~ label {
        color: #f59e0b;
    }
</style>

<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
    <div>
        <h4 class="fw-bold mb-0 text-dark">{{ __('Rate Us') }}</h4>
        <span class="text-muted small">{{ __('Tell us about your experience with Z-pos') }}</span>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm" style="border-radius: 16px;">
            <div class="card-body p-4 p-md-5">



                <form action="{{ route('reviews.store') }}" method="POST">
                    @csrf

                    <div class="text-center mb-4">
                        <label class="form-label text-muted small fw-medium d-block mb-2">{{ __('Your Rating') }}</label>
                        <div class="star-rating">
                            @for($i = 5; $i >= 1; $i--)
                                <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" {{ old('rating', $review->rating ?? 5) == $i ? 'checked' : '' }} required>
                                <label for="star{{ $i }}" title="{{ $i }}"><i class="bi bi-star-fill"></i></label>
                            @endfor
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

                    <p class="text-muted small mb-4">
                        <i class="bi bi-info-circle me-1"></i>
                        {{ __('Only 5-star reviews with written feedback are featured on our homepage.') }}
                    </p>

                    <button type="submit" class="btn btn-success w-100 py-2 fw-semibold" style="border-radius: 8px;">
                        {{ $review ? __('Update Review') : __('Submit Review') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
