@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="card shadow-sm border-0 rounded-4 p-4 text-center" style="max-width: 400px; width: 100%;">
        <i class="bi bi-x-circle-fill text-danger mb-3" style="font-size: 4rem;"></i>
        <h3 class="fw-bold">Account Suspended</h3>
        <p class="text-muted mt-2">
            Your shop account has been suspended by the administrator. Please contact support for more information.
        </p>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
        <button onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="btn btn-outline-secondary w-100 rounded-pill mt-3">
            Logout
        </button>
    </div>
</div>
@endsection
