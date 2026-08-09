<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin Dashboard') - {{ config('app.name', 'Z-pos') }}</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Styles -->
    @vite(['resources/sass/app.scss', 'resources/sass/admin.scss', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="admin-body">
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <div class="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar" class="sidebar" style="background-color: #ffffff !important; border-right: 1px solid #e2e8f0;">
            <div class="sidebar-header py-3 d-flex align-items-center justify-content-center" style="background-color: #ffffff !important; border-bottom: 1px solid #e2e8f0;">
                <div style="width: 100%; height: auto; max-width: 130px; display: flex; justify-content: center; align-items: center; padding: 5px;">
                    @if(Auth::check() && Auth::user()->shop)
                        @if(Auth::user()->shop->logo_path)
                            <img src="{{ asset(Auth::user()->shop->logo_path) }}" alt="{{ Auth::user()->shop->name }}" style="width: 100%; height: auto; object-fit: contain;">
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
                                    <span class="fw-bold lh-1" style="color: #0f172a; font-family: 'Inter', sans-serif; font-size: 1.4rem;">Z-pos</span>
                                </div>
                            </div>
                            <div class="badge mt-2 px-3 py-1 shadow-sm" style="background-color: #0f172a; font-size: 0.7rem; letter-spacing: 0.5px;">SYSTEM ADMIN</div>
                        </div>
                    @else
                        <img src="{{ asset('images/zamar_logo.jpg') }}" alt="ZAMAR STORE" style="width: 100%; height: auto; object-fit: contain;">
                    @endif
                </div>
            </div>

            @if(!Auth::check() || !Auth::user()->hasRole('Super Admin') || Auth::user()->shop_id)
            <ul class="list-unstyled components" style="background-color: #ffffff;">
                <li class="{{ request()->is('/') || request()->is('home') ? 'active' : '' }}">
                    <a href="{{ url('/home') }}">
                        <i class="bi bi-grid-fill me-3"></i> Dashboard
                    </a>
                </li>
                
                <li class="{{ request()->is('sales*') ? 'active' : '' }}">
                    <a href="{{ route('sales.create') }}">
                        <i class="bi bi-cart-check-fill me-3"></i> Sales
                    </a>
                </li>
                <li>
                    <a href="#itemsSubmenu" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle" style="color: #64748b;">
                        <i class="bi bi-box-seam-fill me-3"></i> Items
                    </a>
                    <ul class="collapse list-unstyled" id="itemsSubmenu">
                        <li><a href="{{ route('products.index') }}" style="color: #64748b;">Products</a></li>
                        <li><a href="{{ route('categories.index') }}" style="color: #64748b;">Categories</a></li>
                        <li><a href="{{ route('brands.index') }}" style="color: #64748b;">Brands</a></li>
                        <li><a href="{{ route('units.index') }}" style="color: #64748b;">Units</a></li>
                    </ul>
                </li>
                <!-- Purchases removed for small businesses -->
                <li>
                    <a href="{{ route('expenses.index') }}" style="color: #64748b;"><i class="bi bi-graph-down-arrow me-3"></i> Expenses</a>
                </li>
                <li>
                    <a href="#reportsSubmenu" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle" style="color: #64748b;">
                        <i class="bi bi-pie-chart-fill me-3"></i> Reports
                    </a>
                    <ul class="collapse list-unstyled {{ request()->is('reports*') ? 'show' : '' }}" id="reportsSubmenu">
                        <li><a href="{{ route('reports.index') }}" style="color: #64748b;">Overview</a></li>
                        <li><a href="{{ route('reports.profit_loss') }}" style="color: #64748b;">Profit and Loss</a></li>
                        <li><a href="{{ route('reports.sales') }}" style="color: #64748b;">Sales</a></li>
                        <li><a href="{{ route('reports.expenses') }}" style="color: #64748b;">Expenses</a></li>
                    </ul>
                </li>
                <li class="{{ request()->is('staff*') ? 'active' : '' }}">
                    <a href="{{ route('staff.index') }}" style="color: #64748b;">
                        <i class="bi bi-people-fill me-3"></i> Staff & Users
                    </a>
                </li>
            </ul>
            @endif

            @if(auth()->check() && auth()->user()->hasRole('Super Admin'))
            <ul class="list-unstyled components mt-4" style="background-color: #ffffff;">
                <li class="px-3 mb-2 text-uppercase fw-bold text-muted" style="font-size: 0.75rem; letter-spacing: 1px;">
                    System Admin
                </li>
                <li class="{{ request()->routeIs('superadmin.shops.*') ? 'active' : '' }}">
                    <a href="{{ route('superadmin.shops.index') }}" style="color: #64748b;">
                        <i class="bi bi-buildings-fill me-3"></i> Manage Shops
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

            <div class="sidebar-bottom mt-auto" style="background-color: #f8fafc; border-top: 1px solid #e2e8f0;">
                <div class="maintenance-toggle d-flex align-items-center justify-content-between p-3 bg-white rounded border mb-3 mx-3">
                    <div>
                        <div class="fw-bold text-dark" style="font-size: 0.85rem;">Maintenance</div>
                        <div class="text-muted" style="font-size: 0.75rem;" id="maintenanceStatusText">{{ \Illuminate\Support\Facades\Cache::get('site_maintenance') ? 'Site Down' : 'Site Live' }}</div>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="maintenanceSwitch" {{ \Illuminate\Support\Facades\Cache::get('site_maintenance') ? 'checked' : '' }} onchange="toggleMaintenance()">
                    </div>
                </div>
                <a href="{{ route('superadmin.shops.index') }}" class="user-role px-3 pb-3 d-flex align-items-center justify-content-between text-muted text-decoration-none" style="font-size: 0.75rem; font-weight: 700; letter-spacing: 1px;">
                    SUPER ADMIN <i class="bi bi-chevron-right"></i>
                </a>
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

                    <div class="d-flex align-items-center ms-auto">
                        <div class="dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center text-white" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="position-relative me-2">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=ffffff&color=3b82f6" alt="User" class="rounded-circle shadow-sm" width="40" height="40">
                                    <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-light rounded-circle"></span>
                                </div>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                                @if(Auth::user()->shop)
                                <li><a class="dropdown-item py-2" href="{{ route('shop.settings') }}"><i class="bi bi-shop me-2"></i> Shop Settings</a></li>
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
            <div class="p-4">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <!-- Admin Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('sidebar');
            const content = document.getElementById('content');
            const overlay = document.getElementById('sidebarOverlay');
            const toggleBtn = document.getElementById('sidebarCollapse');

            function toggleSidebar() {
                sidebar.classList.toggle('active');
                if (window.innerWidth <= 768) {
                    overlay.classList.toggle('active');
                } else {
                    content.classList.toggle('active');
                }
            }

            toggleBtn.addEventListener('click', toggleSidebar);
            
            if (overlay) {
                overlay.addEventListener('click', toggleSidebar);
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
