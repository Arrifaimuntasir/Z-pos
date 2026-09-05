<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin Dashboard') - {{ config('app.name', 'Z-pos') }}</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- PWA Meta Tags -->
    <link rel="manifest" href="/manifest.json">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="Z-pos">
    <meta name="theme-color" content="#3b82f6">
    <link rel="apple-touch-icon" href="/images/icon-192.png">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Styles -->
    @vite(['resources/sass/app.scss', 'resources/sass/admin.scss', 'resources/js/app.js'])
    @stack('styles')
    
    <style>
        /* Custom Modern Search Bar */
        .custom-search-bar {
            transition: all 0.3s ease;
            box-shadow: 0 2px 5px rgba(0,0,0,0.02) !important;
        }
        .custom-search-bar:focus-within {
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.25) !important;
        }
        .custom-search-bar input::placeholder {
            color: #94a3b8;
            font-size: 0.95rem;
        }
        @media (max-width: 576px) {
            .custom-search-bar {
                max-width: 100% !important;
            }
            .custom-search-bar .btn-search-text {
                display: none;
            }
            .custom-search-bar .btn-search-icon {
                display: inline-block !important;
            }
        }
    </style>
    
    <style>
        /* Force Sidebar Fixed Position (Bypass Cache) */
        .sidebar {
            position: fixed !important;
            top: 0 !important;
            bottom: 0 !important;
            left: 0 !important;
            height: 100vh !important;
            overflow: hidden !important;
            z-index: 1050 !important;
        }
        .sidebar-header, .sidebar-bottom {
            flex-shrink: 0 !important;
        }
        .sidebar-scroll-area {
            flex: 1 !important;
            overflow-y: auto !important;
            min-height: 0 !important;
        }
        .sidebar-scroll-area::-webkit-scrollbar {
            width: 5px !important;
        }
        .sidebar-scroll-area::-webkit-scrollbar-thumb {
            background-color: #cbd5e1 !important;
            border-radius: 4px !important;
        }
        #content {
            margin-left: 250px !important;
            transition: margin-left 0.3s ease;
        }
        #content.active {
            margin-left: 0 !important;
        }
        .sidebar.active {
            margin-left: -250px !important;
        }
        @media (max-width: 768px) {
            #content {
                margin-left: 0 !important;
            }
            .sidebar {
                margin-left: -260px !important;
                width: 260px !important;
                max-width: 260px !important;
            }
            .sidebar.active {
                margin-left: 0 !important;
            }
        }
        
        /* =============================================
           GLOBAL RESPONSIVE SEARCH TOOLBAR
        ============================================= */
        .search-toolbar {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.5rem;
        }
        .search-toolbar .form-control,
        .search-toolbar .form-select {
            min-width: 0;
            flex: 1 1 140px;
            max-width: 220px;
            font-size: 0.875rem;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            padding: 0.375rem 0.75rem;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }
        .search-toolbar .form-control:focus,
        .search-toolbar .form-select:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59,130,246,0.12);
        }
        .search-toolbar .btn {
            flex-shrink: 0;
            border-radius: 8px;
            font-size: 0.875rem;
            padding: 0.375rem 0.85rem;
        }
        @media (max-width: 575px) {
            .search-toolbar {
                width: 100%;
            }
            .search-toolbar .form-control,
            .search-toolbar .form-select {
                max-width: 100%;
                flex: 1 1 100%;
            }
        }
        .small-toast {
            padding: 0.5rem 0.75rem !important;
            width: auto !important;
            min-height: 40px !important;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08) !important;
            border-radius: 8px !important;
            margin-top: 10px !important;
            display: flex !important;
            align-items: center !important;
        }
        .small-toast .swal2-icon {
            transform: scale(0.5) !important;
            margin: 0 -0.5rem 0 -0.5rem !important;
        }
        .small-toast .swal2-title {
            margin: 0 0.5rem !important;
            font-size: 0.9rem !important;
            font-weight: 600 !important;
            color: #0f172a !important;
            padding-top: 0 !important;
            padding-bottom: 0 !important;
            align-self: center !important;
        }
    </style>
</head>
<body class="admin-body">
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <div class="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar" class="sidebar" style="background-color: #ffffff !important; border-right: 1px solid #e2e8f0;">
            <div class="sidebar-header py-3 d-flex align-items-center justify-content-center" style="background-color: #ffffff !important; border-bottom: 1px solid #e2e8f0; min-height: 85px;">
                <div style="width: 100%; max-width: 150px; display: flex; justify-content: center; align-items: center; padding: 5px;">
                    @if(Auth::check() && Auth::user()->shop)
                        @if(Auth::user()->shop->logo_path)
                            <img src="{{ asset(Auth::user()->shop->logo_path) }}" alt="{{ Auth::user()->shop->name }}" style="max-width: 130px; max-height: 45px; width: auto; height: auto; object-fit: contain;">
                        @else
                            <h4 class="fw-bold text-primary mb-0 text-center" style="word-wrap: break-word;">{{ Auth::user()->shop->name }}</h4>
                        @endif
                    @elseif(Auth::check() && Auth::user()->hasRole('Super Admin'))
                        <div class="d-flex flex-column align-items-center mb-2">
                            <div class="d-flex align-items-center justify-content-center" style="white-space: nowrap;">
                                <div style="width: 35px; height: 35px; overflow: hidden; display: flex; justify-content: center; align-items: center;">
                                    <img src="{{ asset('images/logo_pos.png') }}" alt="Z-pos Icon" style="width: 100%; height: 100%; object-fit: cover; transform: scale(2.2);">
                                </div>
                                <div class="ms-2 d-flex flex-column justify-content-center">
                                    <span class="fw-bold lh-1" style="color: #0f172a; font-family: 'Poppins', sans-serif; font-size: 1.4rem;">{{ __('Z-pos') }}</span>
                                </div>
                            </div>
                            <div class="badge mt-2 px-3 py-1 shadow-sm" style="background-color: #0f172a; font-size: 0.7rem; letter-spacing: 0.5px;">{{ __('SYSTEM ADMIN') }}</div>
                        </div>
                    @else
                        <img src="{{ asset('images/zamar_logo.jpg') }}" alt="ZAMAR STORE" style="max-width: 130px; max-height: 45px; width: auto; height: auto; object-fit: contain;">
                    @endif
                </div>
            </div>

            <div class="sidebar-scroll-area">
            @if(!Auth::check() || !Auth::user()->hasRole('Super Admin') || Auth::user()->shop_id)
            <ul class="list-unstyled components" style="background-color: #ffffff;" id="sidebarMenu">
                <li class="{{ request()->is('/') || request()->is('home') ? 'active' : '' }}">
                    <a href="{{ url('/home') }}">
                        <i class="bi bi-grid-fill me-3"></i> {{ __('Dashboard') }}
                    </a>
                </li>
                
                <li class="{{ request()->is('sales*') ? 'active' : '' }}">
                    <a href="#salesSubmenu" onclick="event.preventDefault();" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle" style="color: #64748b;">
                        <i class="bi bi-cart-check-fill me-3"></i> {{ __('Sales') }}
                    </a>
                    <ul class="collapse list-unstyled" id="salesSubmenu" data-bs-parent="#sidebarMenu">
                        <li><a href="{{ route('sales.create') }}" style="color: #64748b;">{{ __('New Sale') }}</a></li>
                        <li><a href="{{ route('sales.index') }}" style="color: #64748b;">{{ __('Sales History') }}</a></li>
                        @if(Auth::user()->shop && in_array(Auth::user()->shop->business_type, ['Retail / General', 'Electronics / IT']))
                        <li><a href="{{ route('returns.index') }}" style="color: #64748b;">{{ __('Return Invoices') }}</a></li>
                        <li>
                            <a href="{{ route('returns.defective') }}" style="color: #64748b;" class="{{ request()->is('returns/defective*') ? 'fw-semibold text-danger' : '' }}">
                                {{ __('Defective Items') }}
                            </a>
                        </li>
                        @endif
                        <li><a href="{{ route('invoices.index') }}" style="color: #64748b;">{{ __('Invoices') }}</a></li>
                    </ul>
                </li>
                

                @if(Auth::user()->hasRole('Administrator'))
                <li>
                    <a href="#itemsSubmenu" onclick="event.preventDefault();" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle" style="color: #64748b;">
                        <i class="bi bi-box-seam-fill me-3"></i> {{ __('Items') }}
                    </a>
                    <ul class="collapse list-unstyled" id="itemsSubmenu" data-bs-parent="#sidebarMenu">
                        <li><a href="{{ route('products.index') }}" style="color: #64748b;">{{ __('Products') }}</a></li>
                        <li><a href="{{ route('categories.index') }}" style="color: #64748b;">{{ __('Categories') }}</a></li>
                        <li><a href="{{ route('brands.index') }}" style="color: #64748b;">{{ __('Brands') }}</a></li>
                        <li><a href="{{ route('units.index') }}" style="color: #64748b;">{{ __('Units') }}</a></li>
                    </ul>
                </li>
                @if(Auth::user()->hasRole('Administrator'))
                <li>
                    <a href="#purchasesSubmenu" onclick="event.preventDefault();" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle" style="color: #64748b;">
                        <i class="bi bi-bag-plus-fill me-3"></i> {{ __('Purchases') }}
                    </a>
                    <ul class="collapse list-unstyled" id="purchasesSubmenu" data-bs-parent="#sidebarMenu">
                        <li><a href="{{ route('purchases.create') }}" style="color: #64748b;">{{ __('Add Purchase') }}</a></li>
                        <li><a href="{{ route('purchases.index') }}" style="color: #64748b;">{{ __('Purchase History') }}</a></li>
                        <li><a href="{{ route('suppliers.index') }}" style="color: #64748b;">{{ __('Suppliers') }}</a></li>
                    </ul>
                </li>
                @endif
                <li>
                    <a href="{{ route('expenses.index') }}" style="color: #64748b;"><i class="bi bi-graph-down-arrow me-3"></i> {{ __('Expenses') }}</a>
                </li>
                <li>
                    <a href="#reportsSubmenu" onclick="event.preventDefault();" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle" style="color: #64748b;">
                        <i class="bi bi-pie-chart-fill me-3"></i> {{ __('Reports') }}
                    </a>
                    <ul class="collapse list-unstyled" id="reportsSubmenu" data-bs-parent="#sidebarMenu">
                        <li><a href="{{ route('reports.index') }}" style="color: #64748b;">{{ __('Overview') }}</a></li>
                        <li><a href="{{ route('reports.profit_loss') }}" style="color: #64748b;">{{ __('Profit and Loss') }}</a></li>
                        <li><a href="{{ route('reports.sales') }}" style="color: #64748b;">{{ __('Sales') }}</a></li>
                        <li><a href="{{ route('reports.expenses') }}" style="color: #64748b;">{{ __('Expenses') }}</a></li>
                    </ul>
                </li>
                @endif
                @if(Auth::user()->shop && in_array(Auth::user()->shop->package, ['professional', 'enterprise']) && Auth::user()->hasRole('Administrator'))
                <li class="{{ request()->is('branches*') ? 'active' : '' }}">
                    <a href="{{ route('branches.index') }}" style="color: #64748b;">
                        <i class="bi bi-shop me-3"></i> {{ __('Branches') }}
                    </a>
                </li>
                @endif
                
                @if(Auth::user()->shop && in_array(Auth::user()->shop->business_type, ['Electronics / IT']))
                <li class="{{ request()->is('warranties*') ? 'active' : '' }}">
                    <a href="{{ route('warranties.index') }}" style="color: #64748b;">
                        <i class="bi bi-shield-check me-3"></i> {{ __('Warranties') }}
                    </a>
                </li>
                @endif
                
                @if(Auth::user()->shop)
                <li class="{{ request()->routeIs('shop.business-card') ? 'active' : '' }}">
                    <a href="{{ route('shop.business-card') }}" style="color: #64748b;">
                        <i class="bi bi-person-badge-fill me-3"></i> {{ __('Business Card') }}
                    </a>
                </li>
                @endif
                
                @if(Auth::user()->hasRole('Administrator'))
                <li class="{{ request()->is('staff*') ? 'active' : '' }}">
                    <a href="{{ route('staff.index') }}" style="color: #64748b;">
                        <i class="bi bi-people-fill me-3"></i> {{ __('Staff & Users') }}
                    </a>
                </li>
                @endif
            </ul>
            @endif

            @if(auth()->check() && auth()->user()->hasRole('Super Admin'))
            <ul class="list-unstyled components mt-4" style="background-color: #ffffff;">
                <li class="px-3 mb-2 text-uppercase fw-bold text-muted" style="font-size: 0.75rem; letter-spacing: 1px;">
                    {{ __('System Admin') }}
                </li>
                <li class="{{ request()->routeIs('superadmin.shops.*') ? 'active' : '' }}">
                    <a href="{{ route('superadmin.shops.index') }}" style="color: #64748b;">
                        <i class="bi bi-buildings-fill me-3"></i> {{ __('Manage Shops') }}
                    </a>
                </li>
                <li class="{{ request()->routeIs('superadmin.payments.*') ? 'active' : '' }}">
                    <a href="{{ route('superadmin.payments.index') }}" style="color: #64748b;">
                        <i class="bi bi-credit-card-fill me-3"></i> Manage Payments
                        @php
                            $pendingCount = \App\Models\Payment::where('status', 'pending')->count();
                        @endphp
                        @if($pendingCount > 0)
                            <span class="badge bg-danger rounded-pill float-end">{{ $pendingCount }}</span>
                        @endif
                    </a>
                </li>
            </ul>
            @endif
            </div>

            <div class="sidebar-bottom mt-auto" style="background-color: #f8fafc; border-top: 1px solid #e2e8f0;">
                @if(auth()->check() && auth()->user()->hasRole('Super Admin'))
                <div class="maintenance-toggle d-flex align-items-center justify-content-between p-3 bg-white rounded border mb-3 mx-3">
                    <div>
                        <div class="fw-bold text-dark" style="font-size: 0.85rem;">{{ __('Maintenance') }}</div>
                        <div class="text-muted" style="font-size: 0.75rem;" id="maintenanceStatusText">{{ \Illuminate\Support\Facades\Cache::get('site_maintenance') ? 'Site Down' : 'Site Live' }}</div>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="maintenanceSwitch" {{ \Illuminate\Support\Facades\Cache::get('site_maintenance') ? 'checked' : '' }} onchange="toggleMaintenance()">
                    </div>
                </div>
                @endif
                
                @if(auth()->check())
                <div class="px-3 py-3 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center overflow-hidden">
                        <div class="rounded-circle d-flex align-items-center justify-content-center text-dark fw-bold" style="width: 40px; height: 40px; background-color: #facc15; font-size: 1.2rem; flex-shrink: 0;">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="ms-3 overflow-hidden">
                            <div class="fw-bold text-dark text-truncate" style="font-size: 0.9rem;">{{ auth()->user()->name }}</div>
                            <div class="text-muted text-truncate" style="font-size: 0.75rem;">{{ auth()->user()->roles->first()?->name ?? 'User' }}</div>
                        </div>
                    </div>
                    <button class="btn btn-dark text-white rounded-circle shadow-sm d-flex align-items-center justify-content-center" type="button" data-bs-toggle="offcanvas" data-bs-target="#supportOffcanvas" title="{{ __('Customer Support') }}" style="width: 38px; height: 38px; flex-shrink: 0;">
                        <i class="bi bi-headset"></i>
                    </button>
                </div>
                @endif
            </div>
        </nav>
        
        <script>
        function toggleMaintenance() {
            fetch('{{ route('maintenance.toggle') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('maintenanceStatusText').innerText = data.status ? 'Site Down' : 'Site Live';
                alert(data.status ? 'Maintenance Mode is now ON. Cashiers cannot access the system.' : 'Maintenance Mode is now OFF. System is live.');
            })
            .catch(error => console.error('Error:', error));
        }
        </script>

        <!-- Page Content -->
        <div id="content" style="background-color: #f8fafc;">
            <!-- Top Navbar -->
            <nav class="navbar navbar-expand-lg top-navbar shadow-sm border-bottom" style="background-color: #3b82f6 !important; padding: 12px 20px;">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn text-white" style="background: transparent; border: none;">
                        <i class="bi bi-chevron-double-left fs-5"></i>
                    </button>

                    <div class="d-flex align-items-center ms-auto gap-2 gap-sm-3">
                        <!-- Branch Switcher -->
                        @if(Auth::user()->hasRole('Administrator') && Auth::user()->shop && in_array(Auth::user()->shop->package, ['professional', 'enterprise']))
                            @php
                                $userBranches = \App\Models\Branch::where('shop_id', Auth::user()->shop_id)->get();
                            @endphp
                            @if($userBranches->count() > 1)
                            <div class="dropdown">
                                <button class="btn btn-light dropdown-toggle bg-white border d-flex align-items-center rounded-pill px-2 px-sm-3 shadow-sm" type="button" id="branchDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="font-weight: 600; font-size: 0.85rem; color: #334155;">
                                    <i class="bi bi-shop me-1 me-sm-2 text-primary"></i>
                                    @php
                                        $activeBranchId = session('active_branch_id');
                                        $activeBranch = $activeBranchId ? $userBranches->where('id', $activeBranchId)->first() : null;
                                    @endphp
                                    <span class="text-truncate d-inline-block" style="max-width: 90px;">{{ $activeBranch ? $activeBranch->name : __('All Branches') }}</span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2" aria-labelledby="branchDropdown">
                                    <li>
                                        <form action="{{ route('branch.switch') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="branch_id" value="">
                                            <button type="submit" class="dropdown-item py-2 {{ !$activeBranchId ? 'active bg-primary text-white' : '' }}">
                                                <i class="bi bi-globe me-2"></i> {{ __('All Branches (Overall)') }}
                                            </button>
                                        </form>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    @foreach($userBranches as $b)
                                        <li>
                                            <form action="{{ route('branch.switch') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="branch_id" value="{{ $b->id }}">
                                                <button type="submit" class="dropdown-item py-2 {{ $activeBranchId == $b->id ? 'active bg-primary text-white' : '' }}">
                                                    <i class="bi bi-geo-alt me-2"></i> {{ $b->name }}
                                                </button>
                                            </form>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                        @endif

                        <!-- Language Switcher -->
                        <div class="dropdown">
                            <a class="btn btn-light bg-white rounded-pill px-2 px-sm-3 py-1 shadow-sm d-flex align-items-center text-decoration-none dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="border: 1px solid #e2e8f0; font-weight: 600; font-size: 0.85rem; color: #334155; white-space: nowrap;">
                                @if(App::getLocale() == 'en')
                                    <img src="https://flagcdn.com/w20/gb.png" alt="UK" class="me-1" style="width: 18px; border-radius: 2px;"> <span class="d-none d-sm-inline">{{ __('ENG') }}</span>
                                @else
                                    <img src="https://flagcdn.com/w20/tz.png" alt="Tanzania" class="me-1" style="width: 18px; border-radius: 2px;"> <span class="d-none d-sm-inline">{{ __('SW') }}</span>
                                @endif
                            </a>
                            <ul class="dropdown-menu shadow-sm border-0 mt-2" style="min-width: auto; padding: 0.5rem;">
                                <li><a class="dropdown-item d-flex align-items-center py-2" href="{{ route('lang.switch', 'sw') }}"><img src="https://flagcdn.com/w20/tz.png" class="me-2" style="width: 20px; border-radius: 2px;"> {{ __('Swahili') }}</a></li>
                                <li><a class="dropdown-item d-flex align-items-center py-2" href="{{ route('lang.switch', 'en') }}"><img src="https://flagcdn.com/w20/gb.png" class="me-2" style="width: 20px; border-radius: 2px;"> {{ __('English') }}</a></li>
                            </ul>
                        </div>

                        <!-- Notifications Bell -->
                        @if(Auth::user()->hasRole('Administrator'))
                        <a class="text-white position-relative d-flex align-items-center justify-content-center" href="{{ route('notifications.index') }}" style="font-size: 1.25rem; width: 36px; height: 36px; border-radius: 50%; background: rgba(255,255,255,0.15); transition: background 0.2s ease; text-decoration: none;" onmouseover="this.style.background='rgba(255,255,255,0.25)'" onmouseout="this.style.background='rgba(255,255,255,0.15)'">
                            <i class="bi bi-bell-fill" style="font-size: 1.1rem;"></i>
                            @php $unreadCount = Auth::user()->unreadNotifications->count(); @endphp
                            @if($unreadCount > 0)
                                <span class="position-absolute top-0 end-0 badge rounded-pill bg-danger" style="font-size: 0.55rem; min-width: 16px; height: 16px; padding: 0 4px; display: flex; align-items: center; justify-content: center;">
                                    {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                                </span>
                            @endif
                        </a>
                        @else
                        {{-- Cashier notification bell (reads their own) --}}
                        <a class="text-white position-relative d-flex align-items-center justify-content-center" href="{{ route('notifications.index') }}" style="font-size: 1.25rem; width: 36px; height: 36px; border-radius: 50%; background: rgba(255,255,255,0.15); transition: background 0.2s ease; text-decoration: none;" onmouseover="this.style.background='rgba(255,255,255,0.25)'" onmouseout="this.style.background='rgba(255,255,255,0.15)'">
                            <i class="bi bi-bell-fill" style="font-size: 1.1rem;"></i>
                            @php $unreadCount = Auth::user()->unreadNotifications->count(); @endphp
                            @if($unreadCount > 0)
                                <span class="position-absolute top-0 end-0 badge rounded-pill bg-danger" style="font-size: 0.55rem; min-width: 16px; height: 16px; padding: 0 4px; display: flex; align-items: center; justify-content: center;">
                                    {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                                </span>
                            @endif
                        </a>
                        @endif

                        <!-- User Avatar Dropdown -->
                        <div class="dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center text-white p-0" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="position-relative">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=ffffff&color=3b82f6" alt="User" class="rounded-circle shadow-sm" width="36" height="36">
                                    <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-white rounded-circle" style="width:10px;height:10px;"></span>
                                </div>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                                @if(Auth::user()->shop && Auth::user()->hasRole('Administrator'))
                                <li><a class="dropdown-item py-2" href="{{ route('shop.settings') }}"><i class="bi bi-shop me-2"></i> {{ __('Shop Settings') }}</a></li>
                                <li><hr class="dropdown-divider"></li>
                                @endif
                                <li>
                                    <a class="dropdown-item py-2 text-danger" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="bi bi-box-arrow-right me-2"></i> {{ __('Logout') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Main Content Area -->
            <div class="container-fluid py-4 px-3 px-md-4">
                @if(!request()->is('/') && !request()->is('dashboard') && !request()->routeIs('dashboard') && !View::hasSection('hide_back_btn'))
                    <div class="mb-3">
                        <a href="javascript:history.back()" class="btn btn-light border bg-white shadow-sm rounded-pill px-3 fw-bold" style="color: #475569; transition: all 0.2s ease;">
                            <i class="bi bi-arrow-left me-1"></i> {{ __('Back') }}
                        </a>
                    </div>
                @endif
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Main Content Area Ends -->

    <div class="offcanvas offcanvas-end support-offcanvas border-0 shadow" tabindex="-1" id="supportOffcanvas" aria-labelledby="supportOffcanvasLabel">
        <div class="offcanvas-header bg-primary text-white p-4">
            <div class="d-flex align-items-center">
                <div class="bg-white bg-opacity-25 rounded-circle p-2 me-3">
                    <i class="bi bi-headset fs-3"></i>
                </div>
                <div>
                    <h5 class="offcanvas-title fw-bold mb-0" id="supportOffcanvasLabel">{{ __('Customer Support') }}</h5>
                    <small class="text-white-50">{{ __('Need Help with Z-pos?') }}</small>
                </div>
            </div>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="{{ __('Close') }}"></button>
        </div>
        <div class="offcanvas-body p-4 bg-light">
            <p class="text-muted mb-4" style="font-size: 0.95rem;">{{ __('Our support team is always here to assist you. Choose a channel below to reach out.') }}</p>
            
            <a href="https://wa.me/255683628142" target="_blank" class="text-decoration-none">
                <div class="card border-0 shadow-sm rounded-4 mb-3 support-card whatsapp-card">
                    <div class="card-body p-3 d-flex align-items-center">
                        <div class="icon-wrapper bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                            <i class="bi bi-whatsapp fs-4"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold text-dark">{{ __('Chat on WhatsApp') }}</h6>
                            <small class="text-success fw-semibold">+255 683 628 142</small>
                        </div>
                        <i class="bi bi-chevron-right ms-auto text-muted"></i>
                    </div>
                </div>
            </a>

            <a href="tel:+255683628142" class="text-decoration-none">
                <div class="card border-0 shadow-sm rounded-4 mb-3 support-card phone-card">
                    <div class="card-body p-3 d-flex align-items-center">
                        <div class="icon-wrapper bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                            <i class="bi bi-telephone-fill fs-4"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold text-dark">{{ __('Call Us Directly') }}</h6>
                            <small class="text-primary fw-semibold">+255 683 628 142</small>
                        </div>
                        <i class="bi bi-chevron-right ms-auto text-muted"></i>
                    </div>
                </div>
            </a>

            <a href="https://mail.google.com/mail/?view=cm&fs=1&to=info@z-pos.co.tz" target="_blank" class="text-decoration-none">
                <div class="card border-0 shadow-sm rounded-4 mb-3 support-card email-card">
                    <div class="card-body p-3 d-flex align-items-center">
                        <div class="icon-wrapper bg-danger bg-opacity-10 text-danger rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                            <i class="bi bi-envelope-fill fs-4"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold text-dark">{{ __('Email Support') }}</h6>
                            <small class="text-danger fw-semibold">info@z-pos.co.tz</small>
                        </div>
                        <i class="bi bi-chevron-right ms-auto text-muted"></i>
                    </div>
                </div>
            </a>

            <a href="https://instagram.com/zpos.tz" target="_blank" class="text-decoration-none">
                <div class="card border-0 shadow-sm rounded-4 support-card instagram-card">
                    <div class="card-body p-3 d-flex align-items-center">
                        <div class="icon-wrapper bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px; background-color: rgba(225, 48, 108, 0.1); color: #e1306c;">
                            <i class="bi bi-instagram fs-4"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold text-dark">{{ __('Instagram') }}</h6>
                            <small class="fw-semibold" style="color: #e1306c;">@zpos.tz</small>
                        </div>
                        <i class="bi bi-chevron-right ms-auto text-muted"></i>
                    </div>
                </div>
            </a>
            
            <div class="mt-5 text-center">
                <div class="d-flex justify-content-center align-items-center opacity-50 mb-2">
                    <img src="{{ asset('images/logo_pos.png') }}" alt="Z-pos" style="width: 30px;">
                    <span class="fw-bold ms-2" style="font-size: 1.2rem; color: #0f172a;">{{ __('Z-pos') }}</span>
                </div>
                <div class="text-muted small">&copy; {{ date('Y') }} Z-pos. All rights reserved.</div>
            </div>
        </div>
    </div>

    <!-- Admin Script -->
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                }
            });

            @if(session('success'))
                Toast.fire({
                    icon: 'success',
                    title: "{{ session('success') }}"
                });
            @endif

            @if(session('error'))
                Toast.fire({
                    icon: 'error',
                    title: "{{ session('error') }}"
                });
            @endif

            const sidebar = document.getElementById('sidebar');
            const content = document.getElementById('content');
            const overlay = document.getElementById('sidebarOverlay');
            const toggleBtn = document.getElementById('sidebarCollapse');

            function toggleSidebar() {
                if(sidebar) sidebar.classList.toggle('active');
                if (window.innerWidth <= 768) {
                    if(overlay) overlay.classList.toggle('active');
                } else {
                    if(content) content.classList.toggle('active');
                }
            }

            if(toggleBtn) {
                toggleBtn.addEventListener('click', toggleSidebar);
            }
            
            if (overlay) {
                overlay.addEventListener('click', toggleSidebar);
            }

            // Close sidebar on mobile when support offcanvas is opened
            const supportBtn = document.querySelector('[data-bs-target="#supportOffcanvas"]');
            if (supportBtn) {
                supportBtn.addEventListener('click', function() {
                    if (window.innerWidth <= 768 && sidebar && sidebar.classList.contains('active')) {
                        toggleSidebar();
                    }
                });
            }
        });

        // --- Web Push Notifications Logic ---
        const vapidPublicKey = "{{ env('VAPID_PUBLIC_KEY') }}";
        
        function urlB64ToUint8Array(base64String) {
            const padding = '='.repeat((4 - base64String.length % 4) % 4);
            const base64 = (base64String + padding)
                .replace(/\-/g, '+')
                .replace(/_/g, '/');
            const rawData = window.atob(base64);
            const outputArray = new Uint8Array(rawData.length);
            for (let i = 0; i < rawData.length; ++i) {
                outputArray[i] = rawData.charCodeAt(i);
            }
            return outputArray;
        }

        function subscribeUserToPush() {
            navigator.serviceWorker.ready.then(function(registration) {
                const subscribeOptions = {
                    userVisibleOnly: true,
                    applicationServerKey: urlB64ToUint8Array(vapidPublicKey)
                };
                return registration.pushManager.subscribe(subscribeOptions);
            })
            .then(function(pushSubscription) {
                console.log('Received PushSubscription: ', JSON.stringify(pushSubscription));
                sendSubscriptionToBackEnd(pushSubscription);
            })
            .catch(function(err) {
                console.error('Failed to subscribe the user: ', err);
            });
        }

        function sendSubscriptionToBackEnd(subscription) {
            fetch('/push-subscriptions', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(subscription)
            })
            .then(function(response) {
                if (!response.ok) {
                    throw new Error('Bad status code from server.');
                }
                console.log('Successfully saved subscription to server.');
            })
            .catch(function(err) {
                console.error('Failed to save subscription.', err);
            });
        }

        if ('serviceWorker' in navigator && 'PushManager' in window) {
            navigator.serviceWorker.register('/sw.js?v=81')
            .then(function(swReg) {
                console.log('Service Worker is registered', swReg);
                
                // If not denied, prompt user on first click anywhere
                if (Notification.permission === 'default') {
                    const promptOnce = () => {
                        Notification.requestPermission().then(function(permission) {
                            if (permission === 'granted') {
                                subscribeUserToPush();
                            }
                        });
                        document.removeEventListener('click', promptOnce);
                    };
                    document.addEventListener('click', promptOnce);
                } else if (Notification.permission === 'granted') {
                    // Always try to subscribe/refresh subscription on load if granted
                    subscribeUserToPush();
                }
            })
            .catch(function(error) {
                console.error('Service Worker Error', error);
            });
        }
    </script>
    

    @stack('scripts')
</body>
</html>

