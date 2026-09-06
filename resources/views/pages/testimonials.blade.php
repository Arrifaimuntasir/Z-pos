@extends('layouts.landing')
@section('title', 'Testimonials - Z-pos')
@section('content')
@php use App\Models\Testimonial; use App\Models\CmsSetting; @endphp
<div style="padding-top: 100px;">
    <section id="testimonials" class="py-5 bg-light">
        <div class="container py-5">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="fw-bold text-primary display-5">{{ __('Loved by shop owners') }}</h2>
                <p class="text-muted fs-5 mt-3">{{ __('See what our customers are saying about Z-pos.') }}</p>
            </div>

            <div class="row g-4">
                @foreach(Testimonial::active()->orderBy('sort_order')->get() as $index => $t)
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="{{ ($index + 1) * 100 }}">
                    <div class="card border-0 shadow-sm h-100 rounded-4">
                        <div class="card-body p-4">
                            <div class="d-flex text-warning mb-3">
                                @for($s=1;$s<=5;$s++)
                                    <i class="bi bi-star-fill{{ $s > $t->rating ? ' opacity-25' : '' }}"></i>
                                @endfor
                            </div>
                            <p class="fst-italic text-muted mb-4">"{{ $t->quote }}"</p>
                            <div class="d-flex align-items-center mt-auto">
                                <div class="bg-{{ $t->avatar_color }} text-white rounded-circle d-flex justify-content-center align-items-center fw-bold" style="width: 45px; height: 45px;">{{ $t->avatar_initials }}</div>
                                <div class="ms-3">
                                    <h6 class="mb-0 fw-bold">{{ $t->name }}</h6>
                                    <span class="text-muted small">{{ $t->position }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
</div>
@endsection
