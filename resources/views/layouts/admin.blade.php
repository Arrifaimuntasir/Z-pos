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
</head>
<body class="admin-body">
    <div class="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar" class="sidebar" style="background-color: #ffffff !important; border-right: 1px solid #e2e8f0;">
            <div class="sidebar-header py-3 d-flex align-items-center justify-content-center" style="background-color: #ffffff !important; border-bottom: 1px solid #e2e8f0;">
                <div style="width: 100%; height: auto; max-width: 130px; display: flex; justify-content: center; align-items: center; padding: 5px; mix-blend-mode: multiply;">
                    <img src="{{ asset('images/zamar_logo.jpg') }}" alt="ZAMAR STORE" style="width: 100%; height: auto; object-fit: contain; filter: brightness(1.2) contrast(1.8);">
                </div>
            </div>

            <ul class="list-unstyled components" style="background-color: #ffffff;">
                <li class="{{ request()->is('/') || request()->is('home') ? 'active' : '' }}" style="{{ request()->is('/') || request()->is('home') ? 'background-color: #eff6ff; border-radius: 8px; margin: 0 10px;' : 'margin: 0 10px;' }}">
                    <a href="{{ url('/home') }}" style="{{ request()->is('/') || request()->is('home') ? 'color: #2563eb !important;' : 'color: #64748b;' }}"><i class="bi bi-grid-fill me-3" style="{{ request()->is('/') || request()->is('home') ? 'color: #2563eb !important;' : 'color: #64748b;' }}"></i> Dashboard</a>
                </li>
                
                <li class="{{ request()->is('sales*') ? 'active' : '' }}" style="{{ request()->is('sales*') ? 'background-color: #eff6ff; border-radius: 8px; margin: 0 10px;' : 'margin: 0 10px;' }}">
                    <a href="{{ route('sales.create') }}" style="{{ request()->is('sales*') ? 'color: #2563eb !important;' : 'color: #64748b;' }}">
                        <i class="bi bi-cart-check-fill me-3" style="{{ request()->is('sales*') ? 'color: #2563eb !important;' : 'color: #64748b;' }}"></i> POS / Sales
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
                <li>
                    <a href="#purchasesSubmenu" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle" style="color: #64748b;">
                        <i class="bi bi-cart-fill me-3"></i> Purchases
                    </a>
                    <ul class="collapse list-unstyled" id="purchasesSubmenu">
                        <li><a href="{{ route('purchases.create') }}" style="color: #64748b;">Add Purchase</a></li>
                        <li><a href="{{ route('purchases.index') }}" style="color: #64748b;">Purchase List</a></li>
                        <li><a href="{{ route('suppliers.index') }}" style="color: #64748b;">Suppliers</a></li>
                    </ul>
                </li>
                <li>
                    <a href="{{ route('expenses.index') }}" style="color: #64748b;"><i class="bi bi-graph-down-arrow me-3"></i> Expenses</a>
                </li>
                <li>
                    <a href="{{ route('reports.index') }}" style="color: #64748b;"><i class="bi bi-pie-chart-fill me-3"></i> Reports</a>
                </li>
            </ul>

            <div class="sidebar-bottom mt-auto" style="background-color: #f8fafc; border-top: 1px solid #e2e8f0;">
                <div class="maintenance-toggle d-flex align-items-center justify-content-between p-3 bg-white rounded border mb-3 mx-3">
                    <div>
                        <div class="fw-bold text-dark" style="font-size: 0.85rem;">Maintenance</div>
                        <div class="text-muted" style="font-size: 0.75rem;">Site Live</div>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="maintenanceSwitch" checked>
                    </div>
                </div>
                <div class="user-role px-3 pb-3 d-flex align-items-center justify-content-between text-muted" style="font-size: 0.75rem; font-weight: 700; letter-spacing: 1px;">
                    SUPER ADMIN <i class="bi bi-chevron-right"></i>
                </div>
            </div>
        </nav>

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
                                <li><a class="dropdown-item py-2" href="#"><i class="bi bi-person me-2"></i> Profile</a></li>
                                <li><hr class="dropdown-divider"></li>
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
            document.getElementById('sidebarCollapse').addEventListener('click', function () {
                document.getElementById('sidebar').classList.toggle('active');
                document.getElementById('content').classList.toggle('active');
            });
        });
    </script>
</body>
</html>
