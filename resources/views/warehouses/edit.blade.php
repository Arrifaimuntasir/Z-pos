@extends('layouts.admin')
@section('title', __('Edit Warehouse'))

@push('styles')
    @include('warehouses._styles')
@endpush

@section('content')
<div class="gd">
    <div class="mb-4">
        <h2 class="gd-title">{{ __('Edit Warehouse') }}</h2>
        <p class="gd-sub">{{ $warehouse->name }}</p>
    </div>

    @include('warehouses._nav')
    @include('warehouses._form')
</div>
@endsection
