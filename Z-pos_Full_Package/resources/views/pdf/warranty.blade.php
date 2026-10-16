<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Warranty {{ $warranty->warranty_number }}</title>
    <style>
        @page { margin: 20px; size: A4 landscape; }
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; margin: 0; padding: 0; color: #333; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        
        .certificate-wrapper {
            padding: 20px 30px;
            margin: 0 auto;
            border-radius: 5px;
            min-height: 480px;
        }

        /* Default (Theme 1) */
        .theme-1 { border: 8px solid #1e3a8a; background-color: #f8fafc; }
        .theme-1 .cert-header h1 { color: #1e3a8a; }
        .theme-1 .cert-header p { color: #3b82f6; }
        .theme-1 .label { color: #64748b; }
        .theme-1 .value { color: #0f172a; border-bottom: 1px solid #cbd5e1; }
        
        /* Gold (Theme 2) */
        .theme-2 { border: 8px solid #d97706; background-color: #fffbeb; }
        .theme-2 .cert-header h1 { color: #b45309; }
        .theme-2 .cert-header p { color: #d97706; }
        .theme-2 .label { color: #92400e; }
        .theme-2 .value { color: #451a03; border-bottom: 1px dashed #d97706; }
        
        /* Green (Theme 3) */
        .theme-3 { border-left: 15px solid #166534; border-top: 2px solid #166534; border-right: 2px solid #166534; border-bottom: 2px solid #166534; background-color: #f0fdf4; }
        .theme-3 .cert-header h1 { color: #14532d; }
        .theme-3 .cert-header p { color: #22c55e; }
        .theme-3 .label { color: #166534; }
        .theme-3 .value { color: #000; border-bottom: 1px solid #86efac; }
        
        /* Dark (Theme 4) */
        .theme-4 { border: 4px solid #334155; background-color: #0f172a; color: #f8fafc; }
        .theme-4 .cert-header h1 { color: #f8fafc; }
        .theme-4 .cert-header p { color: #94a3b8; }
        .theme-4 .label { color: #94a3b8; }
        .theme-4 .value { color: #fff; border-bottom: 1px solid #334155; }
        .theme-4 .terms-box { background-color: #1e293b; border-color: #334155; }
        
        /* Purple (Theme 5) */
        .theme-5 { border-top: 10px solid #7e22ce; border-bottom: 10px solid #7e22ce; background-color: #faf5ff; }
        .theme-5 .cert-header h1 { color: #581c87; }
        .theme-5 .cert-header p { color: #9333ea; }
        .theme-5 .label { color: #7e22ce; }
        .theme-5 .value { color: #3b0764; border-bottom: 2px solid #e9d5ff; }
        
        /* Classic (Theme 6) */
        .theme-6 { border: 3px double #475569; background-color: #fffdf5; }
        .theme-6 .cert-header h1 { color: #1e293b; }
        .theme-6 .cert-header p { color: #475569; }
        .theme-6 .label { color: #64748b; }
        .theme-6 .value { color: #0f172a; border-bottom: 1px solid #000; text-align: center; }
        
        /* Red (Theme 7) */
        .theme-7 { border: 8px solid #991b1b; background-color: #fef2f2; }
        .theme-7 .cert-header h1 { color: #7f1d1d; }
        .theme-7 .cert-header p { color: #dc2626; }
        .theme-7 .label { color: #b91c1c; }
        .theme-7 .value { color: #450a0a; border-bottom: 1px solid #fecaca; }
        
        /* Minimalist (Theme 8) */
        .theme-8 { border: 1px solid #cbd5e1; background-color: #ffffff; }
        .theme-8 .cert-header h1 { color: #0f172a; font-weight: normal; letter-spacing: 4px; }
        .theme-8 .cert-header p { color: #94a3b8; }
        .theme-8 .label { color: #94a3b8; }
        .theme-8 .value { color: #334155; }
        
        /* Orange (Theme 9) */
        .theme-9 { border-left: 15px solid #ea580c; background-color: #fff7ed; }
        .theme-9 .cert-header { text-align: left; }
        .theme-9 .cert-header h1 { color: #9a3412; }
        .theme-9 .cert-header p { color: #f97316; }
        .theme-9 .label { color: #ea580c; }
        .theme-9 .value { color: #431407; border-bottom: 2px solid #ffedd5; }
        
        /* Cyan (Theme 10) */
        .theme-10 { border-top: 8px solid #0891b2; border-right: 8px solid #0d9488; background-color: #ecfeff; }
        .theme-10 .cert-header h1 { color: #164e63; }
        .theme-10 .cert-header p { color: #06b6d4; }
        .theme-10 .label { color: #0891b2; }
        .theme-10 .value { color: #0f172a; }

        .cert-header { text-align: center; margin-bottom: 15px; }
        .cert-header h1 { font-size: 26px; text-transform: uppercase; margin: 0; letter-spacing: 2px; }
        .cert-header p { font-size: 11px; text-transform: uppercase; letter-spacing: 2px; margin-top: 5px; margin-bottom: 15px;}
        
        table.info-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        table.info-table td { padding: 6px 10px; width: 50%; vertical-align: top; }
        
        .label { font-size: 10px; text-transform: uppercase; font-weight: bold; margin-bottom: 2px; display: block;}
        .value { font-size: 14px; font-weight: bold; padding-bottom: 2px;}
        
        .terms-box {
            padding: 10px;
            font-size: 11px;
            line-height: 1.4;
            margin-bottom: 10px;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
        }
        
        table.footer-table { width: 100%; margin-top: 15px; }
        table.footer-table td { text-align: center; vertical-align: bottom; width: 50%; }
        
    </style>
</head>
<body>
    
    <div class="certificate-wrapper theme-{{ $warranty->design_theme ?? 1 }}">
        <div class="cert-header">
            @if(isset($shop) && $shop->logo_path)
                <?php 
                    $logoPath = public_path($shop->logo_path);
                    $type = pathinfo($logoPath, PATHINFO_EXTENSION);
                    if (file_exists($logoPath)) {
                        $data = file_get_contents($logoPath);
                        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                    } else {
                        $base64 = null;
                    }
                ?>
                @if($base64)
                    <img src="{{ $base64 }}" alt="Logo" style="max-height: 60px; margin-bottom: 10px;">
                @else
                    <h2 style="margin: 0; margin-bottom: 10px; text-transform: uppercase;">{{ $shop->name }}</h2>
                @endif
            @elseif(isset($shop))
                <h2 style="margin: 0; margin-bottom: 10px; text-transform: uppercase;">{{ $shop->name }}</h2>
            @endif
            <h1>{{ __('Warranty Certificate') }}</h1>
            <p>{{ __('Official Coverage Document') }}</p>
        </div>
        
        <table class="info-table">
            <tr>
                <td>
                    <span class="label">{{ __('Warranty Number') }}</span>
                    <div class="value">{{ $warranty->warranty_number }}</div>
                </td>
                <td>
                    <span class="label">{{ __('Customer Name') }}</span>
                    <div class="value">
                        {{ $warranty->customer_name ?? 'N/A' }}
                        @if($warranty->customer_phone)
                            <br><small style="font-size: 11px; font-weight: normal;">{{ $warranty->customer_phone }}</small>
                        @endif
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="label">{{ __('Region') }}</span>
                    <div class="value">{{ $warranty->region ?? 'N/A' }}</div>
                </td>
                <td>
                    <span class="label">{{ __('Gender') }}</span>
                    <div class="value">{{ $warranty->gender ?? 'N/A' }}</div>
                </td>
            </tr>
            <tr>
                <td style="width: 40%;">
                    <span class="label">{{ __('Product Covered') }}</span>
                    <div class="value">{{ $warranty->product_name }}</div>
                </td>
                <td style="width: 30%;">
                    <span class="label">Price (TZS)</span>
                    <div class="value">{{ $warranty->price ? number_format($warranty->price) : 'N/A' }}</div>
                </td>
                <td style="width: 30%;">
                    <span class="label">Serial / IMEI Number</span>
                    <div class="value">{{ $warranty->serial_number ?? 'N/A' }}</div>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="label">{{ __('Duration') }}</span>
                    <div class="value">{{ $warranty->duration }}</div>
                </td>
                <td colspan="2">
                    <span class="label">{{ __('Valid Until') }}</span>
                    <div class="value">{{ \Carbon\Carbon::parse($warranty->end_date)->format('d M Y') }}</div>
                </td>
            </tr>
        </table>
        
        @if($warranty->conditions)
        <div class="terms-box">
            <strong style="display:block; margin-bottom: 5px; font-size: 12px;">{{ __('Terms & Conditions:') }}</strong>
            {!! $warranty->conditions !!}
        </div>
        @endif
        
        <table class="footer-table">
            <tr>
                <td style="text-align: left;">
                    <div class="label">{{ __('Date Issued') }}</div>
                    <div style="font-size: 13px; font-weight: bold;">{{ $warranty->created_at->format('d M Y') }}</div>
                </td>
                <td style="text-align: right;">
                    <div class="label">{{ __('Issued By') }}</div>
                    <div style="font-size: 13px; font-weight: bold;">{{ $warranty->shop->name }}</div>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
