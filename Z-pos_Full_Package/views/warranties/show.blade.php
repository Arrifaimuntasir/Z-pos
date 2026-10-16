<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warranty Certificate - {{ $warranty->warranty_number }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&family=Inter:wght@300;400;600;700&family=Playfair+Display:ital,wght@0,600;1,600&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #f3f4f6;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
        }
        .print-btn-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
        }
        
        /* A5 Landscape approximate size */
        .certificate-wrapper {
            max-width: 750px;
            width: 100%;
            min-height: 530px;
            background: white;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            position: relative;
            margin: 40px auto;
            border-radius: 8px;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .cert-content {
            display: flex;
            flex-direction: column;
            flex-grow: 1;
            padding: 25px 40px; /* Adjust overall padding */
            z-index: 2;
        }

        /* Base Styles */
        .cert-header { text-align: center; margin-bottom: 15px; }
        .cert-title { font-size: 28px; font-weight: bold; margin-bottom: 2px; text-transform: uppercase; letter-spacing: 2px; }
        .cert-subtitle { font-size: 11px; letter-spacing: 4px; text-transform: uppercase; margin-bottom: 10px; }
        
        .cert-body { flex-grow: 1; }
        
        .cert-footer { 
            display: flex; 
            justify-content: space-between; 
            margin-top: 20px; 
            padding-top: 10px; 
        }
        
        .signature-line { border-top: 1px solid #000; width: 200px; text-align: center; padding-top: 5px; font-size: 11px; text-transform: uppercase; }
        .cert-logo { max-height: 45px; margin-bottom: 5px; }

        /* General Info Layout for A5 */
        .row-info { display: flex; flex-wrap: wrap; margin-bottom: 12px; }
        .col-info { padding: 0 8px; box-sizing: border-box; }
        .info-label { font-size: 10px; text-transform: uppercase; margin-bottom: 2px; font-weight: bold; opacity: 0.8; }
        .info-value { font-size: 14px; font-weight: 600; padding-bottom: 2px; word-break: break-word; }
        .terms-box { font-size: 11px; line-height: 1.4; padding: 10px 12px; border-radius: 6px; }

        /* THEMES */
        
        /* 1: Blue Professional (Gradient) */
        .theme-1 { font-family: 'Inter', sans-serif; background: linear-gradient(135deg, #eff6ff 0%, #ffffff 100%); border-top: 15px solid #1e3a8a; border-bottom: 15px solid #3b82f6; }
        .theme-1 .cert-title { color: #1e3a8a; }
        .theme-1 .cert-subtitle { color: #3b82f6; }
        .theme-1 .info-label { color: #64748b; }
        .theme-1 .info-value { color: #0f172a; border-bottom: 1px solid #cbd5e1; }
        .theme-1 .terms-box { background: rgba(30,58,138,0.03); border: 1px solid rgba(30,58,138,0.1); }

        /* 2: Gold Premium (Gradient) */
        .theme-2 { font-family: 'Cinzel', serif; background: linear-gradient(135deg, #fffbeb 0%, #fffdf5 100%); border: 10px solid transparent; border-image: linear-gradient(45deg, #d97706, #fbbf24, #d97706) 1; }
        .theme-2 .cert-title { color: #b45309; background: linear-gradient(to right, #b45309, #d97706); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .theme-2 .cert-subtitle { color: #d97706; }
        .theme-2 .info-label { color: #92400e; font-family: 'Inter', sans-serif; }
        .theme-2 .info-value { color: #451a03; border-bottom: 1px dashed #d97706; font-family: 'Inter', sans-serif;}
        .theme-2 .terms-box { background: rgba(217,119,6,0.05); font-family: 'Inter', sans-serif; }

        /* 3: Green Eco (Gradient) */
        .theme-3 { font-family: 'Inter', sans-serif; background: radial-gradient(circle at top right, #dcfce7, #ffffff); border-left: 20px solid #166534; }
        .theme-3 .cert-title { color: #14532d; }
        .theme-3 .cert-subtitle { color: #22c55e; }
        .theme-3 .info-label { color: #166534; }
        .theme-3 .info-value { color: #000; border-bottom: 1px solid #86efac; }
        .theme-3 .terms-box { background: rgba(22,101,52,0.05); }

        /* 4: Dark Modern (Gradient) */
        .theme-4 { font-family: 'Inter', sans-serif; background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #f8fafc; border: 4px solid #334155; }
        .theme-4 .cert-title { color: #f8fafc; text-shadow: 0 2px 4px rgba(0,0,0,0.5); }
        .theme-4 .cert-subtitle { color: #94a3b8; }
        .theme-4 .info-label { color: #94a3b8; }
        .theme-4 .info-value { color: #fff; border-bottom: 1px solid #334155; }
        .theme-4 .terms-box { background: rgba(255,255,255,0.05); }
        .theme-4 .signature-line { border-top: 1px solid #fff; }

        /* 5: Purple Royal (Mixed Gradient) */
        .theme-5 { font-family: 'Cinzel', serif; background: linear-gradient(to bottom right, #faf5ff, #ffffff); border-top: 15px solid transparent; border-image: linear-gradient(to right, #581c87, #a855f7) 1; }
        .theme-5 .cert-title { color: #581c87; }
        .theme-5 .cert-subtitle { color: #9333ea; }
        .theme-5 .info-label { color: #7e22ce; font-family: 'Inter', sans-serif; }
        .theme-5 .info-value { color: #3b0764; font-family: 'Inter', sans-serif; border-bottom: 2px solid #e9d5ff; }
        .theme-5 .terms-box { background: rgba(88,28,135,0.03); font-family: 'Inter', sans-serif; }

        /* 6: Classic Certificate */
        .theme-6 { font-family: 'Playfair Display', serif; background: #fffdf5; }
        .theme-6 .inner-border { border: 3px double #475569; position: absolute; top: 12px; left: 12px; right: 12px; bottom: 12px; z-index: 1; pointer-events: none; }
        .theme-6 .cert-title { color: #1e293b; font-size: 34px; }
        .theme-6 .cert-subtitle { color: #475569; }
        .theme-6 .info-label { color: #64748b; font-family: 'Inter', sans-serif; }
        .theme-6 .info-value { color: #0f172a; text-align: center; border-bottom: 1px solid #000; font-family: 'Inter', sans-serif;}
        .theme-6 .terms-box { font-family: 'Inter', sans-serif; border: 1px dashed #cbd5e1; }

        /* 7: Red Alert (Gradient) */
        .theme-7 { font-family: 'Inter', sans-serif; background: linear-gradient(to bottom, #fef2f2, #ffffff); border: 8px solid #991b1b; }
        .theme-7::before { content: ""; position: absolute; top: 0; left: 0; right: 0; bottom: 0; border: 1px solid #ef4444; margin: 3px; pointer-events: none; }
        .theme-7 .cert-title { color: #7f1d1d; }
        .theme-7 .cert-subtitle { color: #dc2626; }
        .theme-7 .info-label { color: #b91c1c; }
        .theme-7 .info-value { color: #450a0a; border-bottom: 1px solid #fecaca; }
        .theme-7 .terms-box { background: rgba(185,28,28,0.03); border-left: 3px solid #ef4444; }

        /* 8: Minimalist Light */
        .theme-8 { font-family: 'Inter', sans-serif; border: 1px solid #e2e8f0; background: #ffffff; border-radius: 12px; }
        .theme-8 .cert-title { color: #0f172a; letter-spacing: 6px; font-weight: 300; }
        .theme-8 .cert-subtitle { color: #94a3b8; }
        .theme-8 .info-label { color: #94a3b8; font-weight: 600; }
        .theme-8 .info-value { color: #334155; }
        .theme-8 .terms-box { border: 1px solid #f1f5f9; background: #f8fafc; }

        /* 9: Orange Energetic (Gradient) */
        .theme-9 { font-family: 'Inter', sans-serif; background: linear-gradient(45deg, #fff7ed 0%, #ffffff 100%); border-left: 15px solid #ea580c; border-bottom: 15px solid #f97316; }
        .theme-9 .cert-header { text-align: left; }
        .theme-9 .cert-title { color: #9a3412; }
        .theme-9 .cert-subtitle { color: #f97316; }
        .theme-9 .info-label { color: #ea580c; }
        .theme-9 .info-value { color: #431407; border-bottom: 2px solid #ffedd5; }
        .theme-9 .terms-box { background: rgba(234,88,12,0.04); }

        /* 10: Tech Cyan (Mixed Colors) */
        .theme-10 { font-family: 'Inter', sans-serif; background: linear-gradient(135deg, #ecfeff 0%, #f0fdfa 100%); border-top: 10px solid #0891b2; border-right: 10px solid #0d9488; }
        .theme-10 .cert-title { color: #164e63; }
        .theme-10 .cert-subtitle { color: #06b6d4; }
        .theme-10 .info-label { color: #0891b2; font-family: monospace; }
        .theme-10 .info-value { color: #0f172a; background: #fff; border: 1px solid #cffafe; padding: 4px 8px; border-radius: 4px; }
        .theme-10 .terms-box { background: #fff; border: 1px dashed #5eead4; }

        @media print {
            body { background: white; padding: 0; }
            .print-btn-container { display: none; }
            .certificate-wrapper { box-shadow: none; margin: 0; width: 100%; border-radius: 0; min-height: auto; height: 100vh;}
            /* Adjustments to ensure background colors print */
            * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; color-adjust: exact !important; }
            @page { size: A5 landscape; margin: 0; }
        }

        @media (max-width: 768px) {
            .certificate-wrapper { margin: 60px auto 20px auto; padding: 0; border-width: 5px !important; }
            .cert-content { padding: 15px 20px; }
            .cert-title { font-size: 20px; }
            .cert-subtitle { font-size: 9px; }
            .col-info { width: 100% !important; margin-bottom: 10px; }
            .row-info { display: block; margin-bottom: 0; }
            .cert-footer { flex-direction: column; align-items: center; gap: 15px; }
            .signature-line { width: 100%; }
            .theme-3 { border-left: 10px solid #166534; }
            .theme-6 .inner-border { top: 6px; left: 6px; right: 6px; bottom: 6px; }
        }
    </style>
</head>
<body>

    <div class="print-btn-container d-flex gap-2">
        <a href="{{ route('warranties.pdf', $warranty->id) }}?v={{ time() }}" class="btn btn-sm btn-danger shadow-sm px-3" style="border-radius: 6px;">
            <i class="bi bi-file-earmark-pdf"></i> PDF
        </a>
        <button onclick="window.print()" class="btn btn-sm btn-primary shadow-sm px-3" style="border-radius: 6px;">
            <i class="bi bi-printer"></i> Print Certificate
        </button>
        <button onclick="window.close()" class="btn btn-sm btn-light border shadow-sm px-3" style="border-radius: 6px;">
            Close
        </button>
    </div>

    <div class="certificate-wrapper theme-{{ $warranty->design_theme }}">
        @if($warranty->design_theme == 6)
            <div class="inner-border"></div>
        @endif
        
        <div class="cert-content">
            <div class="cert-header">
                @if($warranty->shop->logo_path)
                    <img src="{{ asset($warranty->shop->logo_path) }}" alt="Logo" class="cert-logo">
                @else
                    <h5 style="font-weight: 900; letter-spacing: 2px; margin-bottom: 5px; margin-top:0;">{{ $warranty->shop->name }}</h5>
                @endif
                <div class="cert-title">WARRANTY CERTIFICATE</div>
                <div class="cert-subtitle">Official Document of Guarantee</div>
                
                <div style="margin-top: 10px; font-size: 10px; opacity: 0.8; font-weight: bold;">
                    Warranty No: <span style="font-size: 12px; color: #000;">{{ $warranty->warranty_number }}</span>
                </div>
            </div>

            <div class="cert-body">
                <div class="row-info">
                    <div class="col-info" style="width: 50%;">
                        <div class="info-label">Customer Name</div>
                        <div class="info-value">{{ $warranty->customer_name ?: 'Valued Customer' }}</div>
                    </div>
                    <div class="col-info" style="width: 50%;">
                        <div class="info-label">Customer Phone</div>
                        <div class="info-value">{{ $warranty->customer_phone ?: 'N/A' }}</div>
                    </div>
                </div>

                <div class="row-info">
                    <div class="col-info" style="width: 50%;">
                        <div class="info-label">Region</div>
                        <div class="info-value">{{ $warranty->region ?: 'N/A' }}</div>
                    </div>
                    <div class="col-info" style="width: 50%;">
                        <div class="info-label">Gender</div>
                        <div class="info-value">{{ $warranty->gender ?: 'N/A' }}</div>
                    </div>
                </div>

                <div class="row-info">
                    <div class="col-info" style="width: 40%;">
                        <div class="info-label">Product Name / Model</div>
                        <div class="info-value">{{ $warranty->product_name }}</div>
                    </div>
                    <div class="col-info" style="width: 30%;">
                        <div class="info-label">Price (TZS)</div>
                        <div class="info-value">{{ $warranty->price ? number_format($warranty->price) : 'N/A' }}</div>
                    </div>
                    <div class="col-info" style="width: 30%;">
                        <div class="info-label">Serial Number / IMEI</div>
                        <div class="info-value">{{ $warranty->serial_number ?: 'N/A' }}</div>
                    </div>
                </div>

                <div class="row-info">
                    <div class="col-info" style="width: 33.3%;">
                        <div class="info-label">Duration</div>
                        <div class="info-value">{{ $warranty->duration }}</div>
                    </div>
                    <div class="col-info" style="width: 33.3%;">
                        <div class="info-label">Valid From</div>
                        <div class="info-value">{{ $warranty->start_date->format('d M, Y') }}</div>
                    </div>
                    <div class="col-info" style="width: 33.3%;">
                        <div class="info-label">Valid Until</div>
                        <div class="info-value">{{ $warranty->end_date->format('d M, Y') }}</div>
                    </div>
                </div>

                @if($warranty->conditions)
                <div style="margin-top: 5px;">
                    <div class="info-label" style="margin-bottom: 3px;">Terms and Conditions</div>
                    <div class="terms-box">
                        {!! $warranty->conditions !!}
                    </div>
                </div>
                @endif
            </div>

            <div class="cert-footer">
                <div style="text-align: center;">
                    <div class="info-label">Date Issued</div>
                    <div style="font-weight: bold; font-size: 13px;">{{ $warranty->created_at->format('d M, Y') }}</div>
                </div>
                <div style="text-align: center;">
                    <div class="info-label">Issued By</div>
                    <div style="font-weight: bold; font-size: 13px;">{{ $warranty->shop->name }}</div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
