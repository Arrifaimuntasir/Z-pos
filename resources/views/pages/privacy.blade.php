@extends('layouts.landing')
@section('title', 'Privacy Policy - Z-pos')
@section('content')
@php use App\Models\CmsSetting; @endphp
<div style="padding-top: 100px;">
    <section class="py-5">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <h2 class="fw-bold text-primary display-5 mb-4">{{ CmsSetting::get('privacy', 'page_title', 'Privacy Policy') }}</h2>
                    <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5" data-aos="fade-up">
                        <div class="text-muted lh-lg" style="white-space: pre-line;">{{ CmsSetting::get('privacy', 'content', '') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
