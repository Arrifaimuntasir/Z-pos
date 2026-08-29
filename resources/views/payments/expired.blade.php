<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Subscription - Z-pos Enterprise</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Styles -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .payment-container {
            width: 100%;
            max-width: 500px;
            padding: 2rem;
        }
        .payment-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            padding: 3rem 2.5rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .payment-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 6px;
            background: linear-gradient(to right, #0ea5e9, #10b981);
        }
        .logo-container {
            width: 70px;
            height: 70px;
            background: white;
            border-radius: 16px;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto 1.5rem auto;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            overflow: hidden;
        }
        .logo-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transform: scale(2.2);
        }
        .btn-custom {
            background: linear-gradient(135deg, #0ea5e9, #3b82f6);
            color: white;
            padding: 0.8rem;
            border-radius: 10px;
            font-weight: 600;
            width: 100%;
            border: none;
            transition: all 0.3s;
        }
        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(59, 130, 246, 0.25);
            color: white;
        }
        .btn-outline-custom {
            background: transparent;
            color: #64748b;
            padding: 0.8rem;
            border-radius: 10px;
            font-weight: 600;
            width: 100%;
            border: 1px solid #e2e8f0;
            transition: all 0.3s;
        }
        .btn-outline-custom:hover {
            background: #f1f5f9;
            color: #334155;
        }
        .price-tag {
            font-size: 2.5rem;
            font-weight: 800;
            color: #0f172a;
            margin: 1.5rem 0;
            letter-spacing: -1px;
        }
        .price-tag span {
            font-size: 1rem;
            font-weight: 500;
            color: #64748b;
            vertical-align: middle;
            margin-left: 5px;
        }
        .custom-file-upload {
            border: 2px dashed #cbd5e1;
            border-radius: 12px;
            padding: 1.5rem;
            background: #f8fafc;
            cursor: pointer;
            transition: all 0.3s;
            margin-bottom: 1.5rem;
        }
        .custom-file-upload:hover {
            border-color: #3b82f6;
            background: #eff6ff;
        }
        .custom-file-upload input[type="file"] {
            display: block;
            width: 100%;
            margin-top: 10px;
        }
        .payment-info-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 1.25rem;
            margin-bottom: 1.5rem;
            text-align: left;
        }
        .payment-info-box h6 {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
            margin-bottom: 0.5rem;
            font-weight: 700;
        }
        .payment-info-box p {
            margin: 0;
            color: #1e293b;
            font-size: 0.95rem;
        }
    </style>
</head>
<body>

    <div class="payment-container">
        <div class="payment-card">
            
            <div class="logo-container">
                <img src="{{ asset('images/logo_pos.png') }}" alt="Z-pos Logo">
            </div>

            @php
                $isNewRegistration = $shop->valid_until && $shop->valid_until->isSameDay(now()->subDay());
                $packagePrice = $shop->package === 'professional' ? '65,000' : ($shop->package === 'starter' ? '15,000' : 'Custom');
            @endphp
            
            @if($isNewRegistration)
                <h4 class="fw-bold mb-2">Complete Your Subscription</h4>
                <p class="text-muted small px-3">
                    You have selected the <strong class="text-primary text-uppercase">{{ $shop->package }}</strong> Plan. 
                    Upload your payment receipt below to activate your account instantly.
                </p>
                <div class="price-tag">
                    {{ $packagePrice }}<span>TZS</span>
                </div>
            @else
                <h4 class="fw-bold mb-2">Subscription Expired</h4>
                <p class="text-muted small px-3">
                    Your shop subscription expired on <strong>{{ $shop->valid_until ? $shop->valid_until->format('d M Y') : 'Unknown' }}</strong>. 
                    Please pay your subscription fee to continue.
                </p>
                <div class="price-tag">
                    {{ $packagePrice }}<span>TZS</span>
                </div>
            @endif

            <div class="payment-info-box shadow-sm">
                <div class="mb-3">
                    <div class="d-flex align-items-center mb-1">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/4/4e/NMB_Bank_Plc.png" height="20" class="me-2" style="object-fit:contain" onerror="this.style.display='none'">
                        <h6>NMB Bank</h6>
                    </div>
                    <p class="fw-bold text-dark fs-5">23710025242</p>
                    <p class="small text-muted">Account Name: MUSTASIR KHAMIS MOHAMED</p>
                </div>
                <hr class="text-muted opacity-25">
                <div>
                    <div class="d-flex align-items-center mb-1">
                        <i class="bi bi-phone-vibrate text-danger me-2 fs-5"></i>
                        <h6>Lipa Namba (Airtel Money / All Networks)</h6>
                    </div>
                    <p class="fw-bold text-danger fs-5">135511433</p>
                    <p class="small text-muted">Name: MUNTASIR MOHAMED</p>
                </div>
            </div>

            @if($pendingPayment)
                <div class="alert alert-primary border-0 bg-primary bg-opacity-10 text-primary rounded-3 small p-3 text-start mb-4">
                    <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-clock-history fs-4 me-3"></i>
                        <strong class="fw-bold">Payment Under Review</strong>
                    </div>
                    You uploaded a receipt on <strong>{{ $pendingPayment->created_at->format('d M Y') }}</strong>. We are currently reviewing it. Please wait for admin approval.
                </div>
                
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
                <button onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="btn-outline-custom">
                    <i class="bi bi-box-arrow-right me-2"></i> Logout for now
                </button>
            @else
                <form action="{{ route('payments.upload') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="custom-file-upload text-start">
                        <label class="form-label fw-bold text-dark mb-1"><i class="bi bi-cloud-arrow-up text-primary me-2"></i> Upload Receipt</label>
                        <p class="text-muted small mb-2" style="font-size: 0.75rem;">Supported formats: JPG, PNG, PDF (Max 2MB)</p>
                        <input type="file" name="receipt" class="form-control form-control-sm" required accept="image/*,.pdf">
                        @error('receipt') <small class="text-danger mt-1 d-block">{{ $message }}</small> @enderror
                    </div>
                    
                    <button type="submit" class="btn-custom mb-3">
                        Submit Payment Receipt
                    </button>
                </form>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="text-muted small text-decoration-none fw-semibold">
                    <i class="bi bi-arrow-left me-1"></i> Back to Login
                </a>
            @endif
        </div>
        
        <div class="text-center mt-4">
            <span class="text-muted small">&copy; {{ date('Y') }} Z-pos Enterprise. All rights reserved.</span>
        </div>
    </div>

</body>
</html>
