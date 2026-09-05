<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('Account Suspended - Z-pos Enterprise') }}</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        body, html {
            min-height: 100vh;
            margin: 0;
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
        }
        body {
            display: flex;
            align-items: flex-start;
            justify-content: center;
        }
        .payment-container {
            width: 100%;
            max-width: 500px;
            margin: auto;
            padding: 2.5rem 1rem;
        }
        .payment-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            padding: 2.5rem 2rem;
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
            background: linear-gradient(to right, #ef4444, #f59e0b);
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
    <div class="payment-container position-relative">
        
        <div class="d-flex justify-content-end w-100 mb-3" style="max-width: 450px;">
            <div class="dropdown">
                <a class="btn btn-light bg-white rounded-pill px-3 py-1 shadow-sm d-flex align-items-center text-decoration-none dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="border: 1px solid #e2e8f0; font-weight: 600; font-size: 0.85rem; color: #334155;">
                    @if(App::getLocale() == 'en')
                        <img src="https://flagcdn.com/w20/gb.png" alt="UK" class="me-1" style="width: 20px; border-radius: 2px;"> <span>{{ __('ENG') }}</span>
                    @else
                        <img src="https://flagcdn.com/w20/tz.png" alt="Tanzania" class="me-1" style="width: 20px; border-radius: 2px;"> <span>{{ __('SW') }}</span>
                    @endif
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2" style="min-width: auto; padding: 0.5rem;">
                    <li><a class="dropdown-item d-flex align-items-center py-2" href="{{ route('lang.switch', 'sw') }}"><img src="https://flagcdn.com/w20/tz.png" class="me-2" style="width: 20px; border-radius: 2px;"> {{ __('Swahili') }}</a></li>
                    <li><a class="dropdown-item d-flex align-items-center py-2" href="{{ route('lang.switch', 'en') }}"><img src="https://flagcdn.com/w20/gb.png" class="me-2" style="width: 20px; border-radius: 2px;"> {{ __('English') }}</a></li>
                </ul>
            </div>
        </div>

        <div class="payment-card">
            
            <div class="logo-container">
                <img src="{{ asset('images/logo_pos.png') }}" alt="Z-pos Logo">
            </div>

            <i class="bi bi-exclamation-triangle-fill text-danger mb-2 d-block" style="font-size: 2.5rem;"></i>
            <h4 class="fw-bold mb-2">{{ __('Account Suspended') }}</h4>
            <p class="text-muted small px-2 mb-4">
                {{ __('Your shop account has been suspended by the administrator. This might be due to pending payments or other violations. Please complete your payment or contact support.') }}
            </p>

            @php
                $amountToPay = '0';
                if ($shop->package === 'starter') $amountToPay = '15,000/=';
                elseif ($shop->package === 'professional') $amountToPay = '45,000/=';
                elseif ($shop->package === 'enterprise') $amountToPay = '110,000/=';
            @endphp
            <div class="alert alert-info text-start shadow-sm mb-4 border-0" style="background-color: #f0fdf4; color: #166534; border-left: 4px solid #16a34a !important;">
                <p class="mb-1 fw-bold"><i class="bi bi-info-circle me-1"></i> {{ __('Subscription Details') }}</p>
                <div class="d-flex justify-content-between align-items-center mt-2">
                    <span class="small">{{ __('Selected Package:') }}</span>
                    <span class="badge bg-success bg-opacity-25 text-success text-uppercase">{{ $shop->package }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-1">
                    <span class="small">{{ __('Amount to Pay:') }}</span>
                    <span class="fw-bold fs-5">TZS {{ $amountToPay }}</span>
                </div>
            </div>

            <div class="payment-info-box shadow-sm">
                <div class="mb-3">
                    <div class="d-flex align-items-center mb-1">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/4/4e/NMB_Bank_Plc.png" height="20" class="me-2" style="object-fit:contain" onerror="this.style.display='none'">
                        <h6>{{ __('NMB Bank') }}</h6>
                    </div>
                    <p class="fw-bold text-dark fs-5">23710025242</p>
                    <p class="small text-muted">{{ __('Account Name: MUSTASIR KHAMIS MOHAMED') }}</p>
                </div>
                <hr class="text-muted opacity-25">
                <div>
                    <div class="d-flex align-items-center mb-1">
                        <i class="bi bi-phone-vibrate text-danger me-2 fs-5"></i>
                        <h6>{{ __('Lipa Namba (Airtel Money / All Networks)') }}</h6>
                    </div>
                    <p class="fw-bold text-danger fs-5">135511433</p>
                    <p class="small text-muted">{{ __('Name: MUNTASIR MOHAMED') }}</p>
                </div>
            </div>

            @if($pendingPayment)
                <div class="alert alert-primary border-0 bg-primary bg-opacity-10 text-primary rounded-3 small p-3 text-start mb-4">
                    <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-clock-history fs-4 me-3"></i>
                        <strong class="fw-bold">{{ __('Payment Under Review') }}</strong>
                    </div>
                    {{ __('You uploaded a receipt on') }} <strong>{{ $pendingPayment->created_at->format('d M Y') }}</strong>{{ __('. We are currently reviewing it. Please wait for admin approval.') }}
                </div>
                
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
                <button onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="btn-outline-custom">
                    <i class="bi bi-box-arrow-right me-2"></i> {{ __('Logout for now') }}
                </button>
            @else
                <form action="{{ route('payments.upload') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="custom-file-upload text-start">
                        <label class="form-label fw-bold text-dark mb-1"><i class="bi bi-cloud-arrow-up text-primary me-2"></i> {{ __('Upload Receipt') }}</label>
                        <p class="text-muted small mb-2" style="font-size: 0.75rem;">{{ __('Take a screenshot of your payment and upload it here (JPG, PNG)') }}</p>
                        <input type="file" name="receipt" class="form-control form-control-sm" required accept="image/*,.pdf">
                        @error('receipt') <small class="text-danger mt-1 d-block">{{ $message }}</small> @enderror
                    </div>
                    
                    <button type="submit" class="btn-custom mb-3">
                        {{ __('Submit Payment Receipt') }}
                    </button>
                </form>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="text-muted small text-decoration-none fw-semibold">
                    <i class="bi bi-arrow-left me-1"></i> {{ __('Back to Login') }}
                </a>
            @endif
        </div>
        
        <div class="text-center mt-4">
            <span class="text-muted small">&copy; {{ date('Y') }} Z-pos Enterprise. All rights reserved.</span>
        </div>
    </div>

</body>
</html>
