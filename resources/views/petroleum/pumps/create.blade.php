@extends('layouts.admin')

@section('content')
<div class="mb-4"><h4 class="fw-bold mb-0 text-dark">{{ __('Add Pump') }}</h4></div>
<div class="card border-0 shadow-sm" style="border-radius: 16px;">
    <div class="card-body p-4">
        <form action="{{ route('petroleum.pumps.store') }}" method="POST">
            @csrf
            @include('petroleum.pumps._form')
            <div class="d-flex gap-2 mt-4">
                <a href="{{ route('petroleum.pumps.index') }}" class="btn btn-light px-4">{{ __('Cancel') }}</a>
                <button type="submit" class="btn btn-primary px-4 fw-bold">{{ __('Save Pump') }}</button>
            </div>
        </form>
    </div>
</div>
@endsection
