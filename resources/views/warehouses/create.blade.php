@extends('layouts.admin')
@section('title', __('Add New Warehouse'))

@push('styles')
    @include('warehouses._styles')
@endpush

@section('content')
<div class="gd">
    <div class="mb-4">
        <h2 class="gd-title">{{ __('Add New Warehouse') }}</h2>
        <p class="gd-sub">{{ __('Create a new warehouse or storage location') }}</p>
    </div>

    @include('warehouses._nav')
    @include('warehouses._form')
</div>
@endsection
