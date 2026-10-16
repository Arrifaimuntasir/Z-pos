@extends('layouts.admin')
@section('title', 'Content Management System')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h4 class="mb-1 fw-bold"><i class="bi bi-pencil-square me-2 text-primary"></i>Content Management System</h4>
            <p class="text-muted small mb-0">Hariri content za ukurasa wa mbele wa Z-pos</p>
        </div>
    </div>



    <div class="row g-4 mb-5">
        @foreach($pages as $slug => $page)
        <div class="col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='translateY(0)'">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-3 d-flex align-items-center justify-content-center me-3 bg-{{ $page['color'] }} bg-opacity-10" style="width:52px;height:52px;">
                            <i class="bi {{ $page['icon'] }} text-{{ $page['color'] }} fs-4"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0">{{ $page['label'] }}</h6>
                            <small class="text-muted">/{{ $slug }}</small>
                        </div>
                    </div>
                    <a href="{{ route('superadmin.cms.edit', $slug) }}" class="btn btn-sm w-100 fw-bold rounded-pill" style="background-color:#64748b;color:white;">
                        <i class="bi bi-pencil me-1"></i> Hariri Content
                    </a>
                </div>
            </div>
        </div>
        @endforeach

        {{-- Testimonials Card --}}
        <div class="col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='translateY(0)'">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-3 d-flex align-items-center justify-content-center me-3 bg-warning bg-opacity-10" style="width:52px;height:52px;">
                            <i class="bi bi-chat-quote-fill text-warning fs-4"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0">Testimonials</h6>
                            <small class="text-muted">/testimonials</small>
                        </div>
                    </div>
                    <a href="{{ route('superadmin.testimonials.index') }}" class="btn btn-sm w-100 fw-bold rounded-pill" style="background-color:#64748b;color:white;">
                        <i class="bi bi-pencil me-1"></i> Simamia Testimonials
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
