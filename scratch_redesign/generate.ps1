$content = @"
@php
    $cat = strtolower($shop->business_type ?? '');
@endphp
@extends('layouts.admin')

@section('title', 'Business Card')

@push('styles')
<style>
/* Add all premium CSS here */
:root {
    --primary: #3b82f6;
    --secondary: #1e293b;
    --card-width: 450px;
    --card-height: 260px;
}
body { background-color: #f8fafc; }
/* ... more CSS ... */
</style>
@endpush

@section('content')
<div class="container-fluid pb-5">
    <!-- UI content -->
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
    // JS Logic
</script>
@endpush
"@
Set-Content -Path "e:\Z-pos\scratch_redesign\card.blade.php" -Value $content -Encoding UTF8
