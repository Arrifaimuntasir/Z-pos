@extends('layouts.admin')
@section('title', 'Add Testimonial')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <h4 class="mb-0 fw-bold"><i class="bi bi-plus-circle me-2 text-warning"></i>Ongeza Testimonial Mpya</h4>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('superadmin.testimonials.store') }}" method="POST">
                        @csrf
                        @include('superadmin.testimonials._form')
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('superadmin.testimonials.index') }}" class="btn btn-outline-secondary rounded-pill px-4">Ghairi</a>
                            <button type="submit" class="btn text-white fw-bold rounded-pill px-5" style="background-color:#64748b;">
                                <i class="bi bi-save me-1"></i> Hifadhi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
