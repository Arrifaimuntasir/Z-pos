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
        <nav id="sidebar" class="sidebar">
            <div class="sidebar-header py-4 d-flex align-items-center justify-content-center">
                <div style="width: 40px; height: 40px; overflow: hidden; display: flex; justify-content: center; align-items: center; background: white; border-radius: 8px;">
                    <img src="{{ asset('images/logo_pos.png') }}" alt="Z-pos Icon" style="width: 100%; height: 100%; object-fit: cover; transform: scale(2.2);">
                </div>
                <div class="ms-2 d-flex flex-column justify-content-center text-start">
                    <span class="fw-bold fs-4 text-primary lh-1">Z-pos</span>
                </div>
            </div>

            <ul class="list-unstyled components">
                <li class="active">
                    <a href="{{ url('/home') }}"><i class="bi bi-grid-fill me-3"></i> Dashboard</a>
                </li>
                <li>
                    <a href="{{ route('customers.index') }}"><i class="bi bi-person-fill me-3"></i> Customer</a>
                </li>
                <li>
                    <a href="#itemsSubmenu" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <i class="bi bi-box-seam-fill me-3"></i> Items
                    </a>
                    <ul class="collapse list-unstyled" id="itemsSubmenu">
                        <li><a href="{{ route('products.index') }}">Products</a></li>
                        <li><a href="{{ route('categories.index') }}">Categories</a></li>
                        <li><a href="{{ route('brands.index') }}">Brands</a></li>
                        <li><a href="{{ route('units.index') }}">Units</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#"><i class="bi bi-receipt-cutoff me-3"></i> Sales Order</a>
                </li>
                <li>
                    <a href="{{ route('suppliers.index') }}"><i class="bi bi-cart-fill me-3"></i> Purchases</a>
                </li>
                <li>
                    <a href="#"><i class="bi bi-bank2 me-3"></i> Banking</a>
                </li>
                <li>
                    <a href="#"><i class="bi bi-pie-chart-fill me-3"></i> Reports</a>
                </li>
                <li>
                    <a href="#"><i class="bi bi-folder-fill me-3"></i> Files</a>
                </li>
                <li>
                    <a href="#"><i class="bi bi-three-dots me-3"></i> More</a>
                </li>
            </ul>

            <div class="sidebar-bottom mt-auto">
                <div class="maintenance-toggle">
                    <div>
                        <div class="fw-bold text-dark">Maintenance</div>
                        <div class="text-muted">Site Live</div>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="maintenanceSwitch" checked>
                    </div>
                </div>
                <div class="user-role">
                    SUPER ADMIN <i class="bi bi-chevron-right"></i>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <div id="content">
            <!-- Top Navbar -->
            <nav class="navbar navbar-expand-lg navbar-light bg-white top-navbar shadow-sm border-bottom">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn btn-light" style="background: transparent;">
                        <i class="bi bi-chevron-double-left fs-5"></i>
                    </button>

                    <div class="d-flex align-items-center ms-auto">
                        <a href="#" class="btn btn-primary rounded-pill px-4 me-3 fw-bold shadow-sm">New Order</a>
                        
                        <a href="#" class="text-dark me-4 position-relative">
                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="bi bi-basket-fill fs-5"></i>
                            </div>
                        </a>

                        <div class="dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center text-dark" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="position-relative me-2">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=0D8ABC&color=fff" alt="User" class="rounded-circle shadow-sm" width="40" height="40">
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
