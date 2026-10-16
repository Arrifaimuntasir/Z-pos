<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $reportTitle }} - {{ $shop->name ?? 'Z-POS' }}</title>
    <style>
        @page { margin: 30px; }
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 12px; margin: 0; padding: 0; color: #333; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 8px 10px; border: 1px solid #ddd; }
        th { background-color: #f8f9fa; font-weight: bold; text-align: left; font-size: 11px; text-transform: uppercase; letter-spacing: 0.3px; }
        .header-table { width: 100%; border: none; margin-bottom: 15px; }
        .header-table td { border: none; padding: 0; vertical-align: top; }
        .company-name { font-size: 20px; font-weight: bold; color: #2c3e50; margin-bottom: 4px; }
        .report-title { font-size: 22px; color: #2c3e50; font-weight: bold; text-transform: uppercase; margin-bottom: 6px; }
        .meta { color: #666; font-size: 11px; line-height: 1.6; }
        .kpi-table td { border: 1px solid #e2e8f0; padding: 12px; width: 25%; }
        .kpi-label { font-size: 10px; color: #888; text-transform: uppercase; letter-spacing: 0.3px; }
        .kpi-value { font-size: 16px; font-weight: bold; color: #1e293b; margin-top: 3px; }
        .section-title { font-size: 14px; font-weight: bold; color: #2c3e50; margin: 18px 0 8px; border-bottom: 2px solid #2c3e50; padding-bottom: 4px; }
        .total-row td { font-weight: bold; border-top: 2px solid #333; background: #f8f9fa; }
        .text-danger { color: #dc3545; }
        .text-success { color: #198754; }
        .footer-note { margin-top: 25px; font-size: 10px; color: #999; border-top: 1px solid #eee; padding-top: 8px; }
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td style="width: 55%;">
                @php
                    $logoBase64 = null;
                    if ($shop && $shop->logo_path) {
                        $logoPath = public_path($shop->logo_path);
                        if (file_exists($logoPath)) {
                            $type = pathinfo($logoPath, PATHINFO_EXTENSION);
                            $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode(file_get_contents($logoPath));
                        }
                    }
                @endphp
                @if($logoBase64)
                    <img src="{{ $logoBase64 }}" alt="Logo" style="max-height: 60px; margin-bottom: 8px;">
                @else
                    <div class="company-name">{{ $shop->name ?? 'Z-POS' }}</div>
                @endif
                @if($shop && $shop->address)<div class="meta">{{ $shop->address }}</div>@endif
                @if($shop && $shop->phone)<div class="meta">{{ $shop->phone }}</div>@endif
                @if($branch)<div class="meta">{{ __('Branch') }}: {{ $branch->name }}</div>@endif
            </td>
            <td style="width: 45%;" class="text-right">
                <div class="report-title">{{ $reportTitle }}</div>
                @isset($dateRangeLabel)
                    <div class="meta">{{ $dateRangeLabel }}</div>
                @endisset
                <div class="meta">
                    {{ __('Generated') }}: {{ $generatedAt->format('d M Y, H:i') }}<br>
                    {{ __('By') }}: {{ trim(($generatedBy->first_name ?? '') . ' ' . ($generatedBy->last_name ?? '')) ?: ($generatedBy->email ?? '-') }}
                </div>
            </td>
        </tr>
    </table>
