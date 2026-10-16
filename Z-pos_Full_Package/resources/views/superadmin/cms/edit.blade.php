@extends('layouts.admin')
@section('title', 'Edit Content - ' . $pageInfo['label'])

@section('content')
<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="row mb-4 align-items-center">
        <div class="col">
            <h4 class="mb-0 fw-bold">
                <i class="bi {{ $pageInfo['icon'] }} me-2 text-{{ $pageInfo['color'] }}"></i>
                Hariri: {{ $pageInfo['label'] }}
            </h4>
        </div>
        <div class="col-auto">
            <a href="{{ url('/' . ($page === 'about' ? 'about' : $page)) }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill">
                <i class="bi bi-box-arrow-up-right me-1"></i> Angalia Ukurasa
            </a>
        </div>
    </div>



    <form action="{{ route('superadmin.cms.update', $page) }}" method="POST">
        @csrf

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-bottom rounded-top-4 p-4">
                <h6 class="fw-bold mb-0 text-muted">
                    <i class="bi bi-sliders me-2"></i>Maudhui ya Ukurasa — {{ count($settings) }} Sehemu
                </h6>
            </div>
            <div class="card-body p-4">
                @forelse($settings as $setting)
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-dark" for="field_{{ $setting->key }}">
                            {{ $setting->label }}
                            <span class="badge bg-light text-muted border ms-2 fw-normal" style="font-size:0.7rem;">{{ $setting->key }}</span>
                        </label>

                        @if($setting->type === 'textarea')
                            <textarea
                                id="field_{{ $setting->key }}"
                                name="{{ $setting->key }}"
                                class="form-control @error($setting->key) is-invalid @enderror"
                                rows="4"
                                style="border-radius:12px; border:1px solid #e2e8f0; background:#f8fafc; resize:vertical;"
                            >{{ old($setting->key, $setting->value) }}</textarea>
                        @else
                            <input
                                type="text"
                                id="field_{{ $setting->key }}"
                                name="{{ $setting->key }}"
                                class="form-control @error($setting->key) is-invalid @enderror"
                                value="{{ old($setting->key, $setting->value) }}"
                                style="border-radius:12px; border:1px solid #e2e8f0; background:#f8fafc;"
                            >
                        @endif

                        @error($setting->key)
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    @if(!$loop->last)<hr style="border-color:#f1f5f9;">@endif
                @empty
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-inbox fs-1"></i>
                        <p class="mt-3">Hakuna fields kwa ukurasa huu bado.</p>
                    </div>
                @endforelse
            </div>
            <div class="card-footer bg-white border-top p-4 rounded-bottom-4">
                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ route('superadmin.cms.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="bi bi-x-circle me-1"></i> Ghairi
                    </a>
                    <button type="submit" class="btn text-white fw-bold rounded-pill px-5" style="background-color:#64748b;">
                        <i class="bi bi-save me-1"></i> Hifadhi Mabadiliko
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
