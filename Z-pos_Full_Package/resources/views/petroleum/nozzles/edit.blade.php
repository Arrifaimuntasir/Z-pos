@extends('layouts.admin')

@section('content')
<div class="mb-4"><h4 class="fw-bold mb-0 text-dark">{{ __('Edit Nozzle') }} {{ $nozzle->number }}</h4></div>
<div class="card border-0 shadow-sm" style="border-radius: 16px;">
    <div class="card-body p-4">
        <form action="{{ route('petroleum.nozzles.update', $nozzle) }}" method="POST">
            @csrf @method('PUT')
            @include('petroleum.nozzles._form')
            <div class="d-flex gap-2 mt-4">
                <a href="{{ route('petroleum.nozzles.index') }}" class="btn btn-light px-4">{{ __('Cancel') }}</a>
                <button type="submit" class="btn btn-primary px-4 fw-bold">{{ __('Update Nozzle') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection
