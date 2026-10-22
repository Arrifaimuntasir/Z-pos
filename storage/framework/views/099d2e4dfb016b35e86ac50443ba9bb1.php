<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $__env->yieldContent('title', 'Admin Dashboard'); ?> - <?php echo e(config('app.name', 'Z-pos')); ?></title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <!-- PWA Meta Tags -->
    <link rel="manifest" href="/manifest.json">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="Z-pos">
    <meta name="theme-color" content="#3b82f6">
    <link rel="apple-touch-icon" href="/images/icon-192.png">

    <!-- Theme Mode Pre-init Script (Zero-Flash & Shop-Scoped) -->
    <?php
        $shopTheme = (Auth::check() && Auth::user()->shop) ? (Auth::user()->shop->theme ?? 'light') : 'light';
        $activeShopId = Auth::check() ? (Auth::user()->shop_id ?? 0) : 0;
    ?>
    <script>
        (function() {
            const shopId = '<?php echo e($activeShopId); ?>';
            const serverTheme = '<?php echo e($shopTheme); ?>';
            const localTheme = shopId ? localStorage.getItem('zpos_theme_shop_' + shopId) : null;
            const activeTheme = localTheme || serverTheme || 'light';
            if (activeTheme === 'dark') {
                document.documentElement.setAttribute('data-theme', 'dark');
                document.documentElement.classList.add('dark-mode');
            } else {
                document.documentElement.setAttribute('data-theme', 'light');
                document.documentElement.classList.remove('dark-mode');
            }
        })();
    </script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Flatpickr Datepicker (Official Fast CDN + Local Fallback) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="<?php echo e(asset('vendor/flatpickr/flatpickr.min.css')); ?>">

    <!-- Styles -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/sass/app.scss', 'resources/sass/admin.scss', 'resources/js/app.js']); ?>
    <?php echo $__env->yieldPushContent('styles'); ?>
    
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
    
        /* =============================================
           MODERN ENTERPRISE SAAS SIDEBAR
        ============================================= */
        :root {
            --sb-width: 256px;
            --sb-bg: #ffffff;
            --sb-border: #e2e8f0;
            --sb-text: #0f172a;
            --sb-text-muted: #334155;
            --sb-hover: #f8fafc;
            --sb-active-bg: #eff6ff;
            --sb-active-text: #1d4ed8;
            --sb-brand: #2563eb;
        }
        .sidebar {
            position: fixed !important;
            top: 0 !important;
            bottom: 0 !important;
            left: 0 !important;
            width: var(--sb-width) !important;
            min-width: var(--sb-width) !important;
            max-width: var(--sb-width) !important;
            height: 100vh !important;
            height: 100dvh !important;
            overflow: hidden !important;
            z-index: 1050 !important;
            background: var(--sb-bg) !important;
            border-right: 1px solid var(--sb-border) !important;
            display: flex !important;
            flex-direction: column !important;
            transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1), margin-left 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
            box-shadow: 2px 0 14px rgba(15, 23, 42, 0.04) !important;
        }
        #content {
            margin-left: var(--sb-width) !important;
            width: calc(100% - var(--sb-width)) !important;
            max-width: calc(100% - var(--sb-width)) !important;
            min-height: 100vh;
            overflow-x: hidden !important;
            transition: margin-left 0.25s cubic-bezier(0.4, 0, 0.2, 1), width 0.25s cubic-bezier(0.4, 0, 0.2, 1), max-width 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }
        #content.active {
            margin-left: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
        }
        .sidebar.active {
            margin-left: calc(-1 * var(--sb-width)) !important;
        }
        @media (max-width: 768px) {
            #content {
                margin-left: 0 !important;
                width: 100% !important;
                max-width: 100% !important;
                overflow-x: hidden !important;
            }
            .sidebar {
                margin-left: calc(-1 * var(--sb-width)) !important;
            }
            .sidebar.active {
                margin-left: 0 !important;
                box-shadow: 0 0 30px rgba(15, 23, 42, 0.22) !important;
            }
        }

        /* Sidebar Brand & Logo Header */
        .sidebar-header {
            min-height: 64px !important;
            height: 64px !important;
            max-height: 64px !important;
            padding: 8px 16px !important;
            background: #ffffff !important;
            border-bottom: 1px solid var(--sb-border) !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            flex-shrink: 0 !important;
            z-index: 10 !important;
        }
        .sidebar-brand-box {
            width: 100% !important;
            height: 100% !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }
        .sidebar-brand-logo {
            max-width: 190px !important;
            max-height: 48px !important;
            width: auto !important;
            height: auto !important;
            object-fit: contain !important;
            transition: transform 0.18s ease !important;
        }
        .sidebar-brand-logo:hover {
            transform: scale(1.02);
        }
        .sidebar-text-brand {
            display: flex !important;
            align-items: center !important;
            gap: 10px !important;
            width: 100% !important;
        }
        .sidebar-brand-avatar {
            width: 36px !important;
            height: 36px !important;
            border-radius: 9px !important;
            background: #0f172a !important;
            color: #facc15 !important;
            font-size: 16px !important;
            font-weight: 700 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            flex-shrink: 0 !important;
            box-shadow: 0 2px 6px rgba(15, 23, 42, 0.15) !important;
        }
        .sidebar-brand-name {
            font-size: 13.5px !important;
            font-weight: 700 !important;
            color: #0f172a !important;
            line-height: 1.25 !important;
            word-break: break-word;
        }
        .sidebar-brand-badge {
            display: inline-block !important;
            font-size: 10.5px !important;
            font-weight: 600 !important;
            color: #475569 !important;
            background: #f1f5f9 !important;
            padding: 1px 7px !important;
            border-radius: 4px !important;
            margin-top: 2px !important;
            border: 1px solid #e2e8f0 !important;
        }

        /* Navigation Menu & Headings */
        .sidebar-scroll-area {
            flex: 1 1 auto !important;
            min-height: 0 !important;
            overflow-y: auto !important;
            overflow-x: hidden !important;
            -webkit-overflow-scrolling: touch !important;
            padding: 10px 10px 14px !important;
        }
        .sidebar-scroll-area::-webkit-scrollbar {
            width: 4px !important;
        }
        .sidebar-scroll-area::-webkit-scrollbar-thumb {
            background-color: #cbd5e1 !important;
            border-radius: 4px !important;
        }
        .sidebar-section-title {
            display: none !important;
        }
        #sidebar ul.components {
            padding: 0 !important;
            margin: 0 !important;
            list-style: none !important;
        }
        #sidebar ul.components li {
            padding: 2px 0 !important;
            list-style: none !important;
        }
        #sidebar ul.components li a.sidebar-nav-link,
        #sidebar .sidebar-nav-link {
            display: flex !important;
            align-items: center !important;
            padding: 9px 12px 9px 14px !important;
            margin: 2px 6px !important;
            border-radius: 8px !important;
            color: #1e293b !important;
            font-size: 13.5px !important;
            font-weight: 600 !important;
            line-height: 1.4 !important;
            text-decoration: none !important;
            border: 1px solid transparent !important;
            transition: all 0.15s cubic-bezier(0.4, 0, 0.2, 1) !important;
            position: relative;
        }
        #sidebar .sidebar-nav-link:hover {
            color: #0f172a !important;
            background: #f8fafc !important;
            border-color: #e2e8f0 !important;
        }
        /* Active State - Premium Light Royal Blue SaaS Style with Left Indicator */
        #sidebar ul.components li.active > a.sidebar-nav-link,
        #sidebar ul.components li.active > .sidebar-nav-link,
        #sidebar li.active > .sidebar-nav-link,
        #sidebar .sidebar-nav-link.active {
            background: #eff6ff !important;
            color: #1d4ed8 !important;
            font-weight: 700 !important;
            border: 1px solid #bfdbfe !important;
            border-left: 4px solid #2563eb !important;
            box-shadow: 0 1px 3px rgba(37, 99, 235, 0.1) !important;
        }
        #sidebar ul.components li.active > a.sidebar-nav-link .nav-label,
        #sidebar ul.components li.active > .sidebar-nav-link .nav-label,
        #sidebar li.active > .sidebar-nav-link .nav-label,
        #sidebar .sidebar-nav-link.active .nav-label {
            color: #1d4ed8 !important;
            font-weight: 700 !important;
        }
        .nav-icon {
            width: 24px !important;
            height: 24px !important;
            font-size: 17px !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            margin-right: 10px !important;
            flex-shrink: 0 !important;
            transition: transform 0.18s ease !important;
        }
        .sidebar-nav-link:hover .nav-icon {
            transform: scale(1.15) !important;
        }
        .nav-icon i {
            color: inherit !important;
            opacity: 1 !important;
            display: inline-flex !important;
        }

        /* Light Mode Icon Palette (Matching user reference colors) */
        .nav-icon.nav-icon-dashboard, .nav-icon.nav-icon-dashboard i { color: #4f46e5 !important; }
        .nav-icon.nav-icon-sales, .nav-icon.nav-icon-sales i { color: #10b981 !important; }
        .nav-icon.nav-icon-items, .nav-icon.nav-icon-items i { color: #3b82f6 !important; }
        .nav-icon.nav-icon-purchases, .nav-icon.nav-icon-purchases i { color: #8b5cf6 !important; }
        .nav-icon.nav-icon-warehouse, .nav-icon.nav-icon-warehouse i { color: #f59e0b !important; }
        .nav-icon.nav-icon-warranties, .nav-icon.nav-icon-warranties i { color: #0d9488 !important; }
        .nav-icon.nav-icon-petroleum, .nav-icon.nav-icon-petroleum i { color: #ea580c !important; }
        .nav-icon.nav-icon-station-day, .nav-icon.nav-icon-station-day i { color: #0284c7 !important; }
        .nav-icon.nav-icon-invoices, .nav-icon.nav-icon-invoices i { color: #4f46e5 !important; }
        .nav-icon.nav-icon-customers, .nav-icon.nav-icon-customers i { color: #3b82f6 !important; }
        .nav-icon.nav-icon-station-setup, .nav-icon.nav-icon-station-setup i { color: #64748b !important; }
        .nav-icon.nav-icon-expenses, .nav-icon.nav-icon-expenses i { color: #ef4444 !important; }
        .nav-icon.nav-icon-reports, .nav-icon.nav-icon-reports i { color: #0ea5e9 !important; }
        .nav-icon.nav-icon-branches, .nav-icon.nav-icon-branches i { color: #8b5cf6 !important; }
        .nav-icon.nav-icon-staff, .nav-icon.nav-icon-staff i { color: #06b6d4 !important; }
        .nav-icon.nav-icon-card, .nav-icon.nav-icon-card i { color: #10b981 !important; }
        .nav-icon.nav-icon-settings, .nav-icon.nav-icon-settings i { color: #475569 !important; }
        .nav-icon.nav-icon-plans, .nav-icon.nav-icon-plans i { color: #f59e0b !important; }
        .nav-icon.nav-icon-reviews, .nav-icon.nav-icon-reviews i { color: #eab308 !important; }
        .nav-icon.nav-icon-superadmin-shops, .nav-icon.nav-icon-superadmin-shops i { color: #4f46e5 !important; }
        .nav-icon.nav-icon-superadmin-payments, .nav-icon.nav-icon-superadmin-payments i { color: #10b981 !important; }
        .nav-icon.nav-icon-superadmin-cms, .nav-icon.nav-icon-superadmin-cms i { color: #8b5cf6 !important; }

        .nav-label {
            font-size: 13.5px !important;
            font-weight: inherit !important;
        }
        .sidebar-nav-link.dropdown-toggle::after {
            display: none !important;
        }
        .menu-chevron {
            margin-left: auto !important;
            width: 22px !important;
            height: 22px !important;
            border-radius: 6px !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            font-size: 11px !important;
            color: #64748b !important;
            background: #f1f5f9 !important;
            transition: transform 0.2s cubic-bezier(0.4, 0, 0.2, 1), background-color 0.2s ease, color 0.2s ease !important;
            flex-shrink: 0 !important;
        }
        .sidebar-nav-link:hover .menu-chevron {
            color: #0f172a !important;
            background: #e2e8f0 !important;
        }
        [aria-expanded="true"] .menu-chevron {
            transform: rotate(180deg) !important;
            color: #2563eb !important;
            background: #eff6ff !important;
        }
        html:not([data-theme="dark"]) body:not(.dark-mode) #sidebar .sidebar-nav-link[aria-expanded="true"],
        html:not([data-theme="dark"]) body:not(.dark-mode) #sidebar a.sidebar-nav-link[aria-expanded="true"],
        html:not([data-theme="dark"]) body:not(.dark-mode) .custom-dropdown-parent.active-dropdown > .sidebar-nav-link {
            background: #f1f5f9 !important;
            color: #0f172a !important;
            font-weight: 700 !important;
            border-color: #e2e8f0 !important;
        }
        html:not([data-theme="dark"]) body:not(.dark-mode) #sidebar .sidebar-nav-link[aria-expanded="true"] .nav-label,
        html:not([data-theme="dark"]) body:not(.dark-mode) #sidebar a.sidebar-nav-link[aria-expanded="true"] .nav-label,
        html:not([data-theme="dark"]) body:not(.dark-mode) .custom-dropdown-parent.active-dropdown > .sidebar-nav-link .nav-label {
            color: #0f172a !important;
            font-weight: 700 !important;
        }

        /* Modern Submenus with Restored Dots (•) - Indented forward from parent header */
        #sidebar .custom-submenu,
        #sidebar ul.custom-submenu,
        #sidebar ul.collapse,
        #sidebar ul.collapsing,
        #sidebar .collapse.show {
            border-left: 2px solid #e2e8f0 !important;
            margin: 4px 6px 6px 30px !important;
            padding: 2px 0 2px 8px !important;
            list-style: none !important;
            background: transparent !important;
            background-color: transparent !important;
            box-shadow: none !important;
        }
        #sidebar .custom-submenu li {
            padding: 1px 0 !important;
            list-style: none !important;
            background: transparent !important;
            background-color: transparent !important;
        }
        #sidebar .custom-submenu li a {
            position: relative !important;
            padding: 7px 12px 7px 28px !important;
            font-size: 13px !important;
            color: #1e293b !important;
            display: flex !important;
            align-items: center !important;
            text-decoration: none !important;
            border-radius: 6px !important;
            border: 1px solid transparent !important;
            transition: all 0.15s ease !important;
            font-weight: 600 !important;
            line-height: 1.35 !important;
            background: transparent !important;
            background-color: transparent !important;
        }
        #sidebar .custom-submenu li a::before {
            content: "•" !important;
            position: absolute !important;
            left: 11px !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            font-size: 18px !important;
            color: #64748b !important;
            line-height: 0 !important;
            transition: color 0.15s ease, transform 0.15s ease !important;
        }
        #sidebar .custom-submenu li a:hover {
            color: #0f172a !important;
            background: #f8fafc !important;
            border-color: #e2e8f0 !important;
        }
        #sidebar .custom-submenu li a:hover::before {
            color: #2563eb !important;
            transform: translateY(-50%) scale(1.3) !important;
        }
        #sidebar .custom-submenu li a.active {
            color: #1d4ed8 !important;
            background: #eff6ff !important;
            border-color: #bfdbfe !important;
            font-weight: 700 !important;
        }
        #sidebar .custom-submenu li a.active::before {
            color: #2563eb !important;
            transform: translateY(-50%) scale(1.3) !important;
        }

        /* Footer & User Profile - 100% FIXED AT BOTTOM */
        .sidebar-bottom {
            flex-shrink: 0 !important;
            flex-grow: 0 !important;
            position: relative !important;
            width: 100% !important;
            background: #f8fafc !important;
            border-top: 1px solid #e2e8f0 !important;
            box-shadow: 0 -3px 12px rgba(15, 23, 42, 0.04) !important;
            z-index: 20 !important;
            padding: 10px 12px !important;
        }

        /* =============================================
           ENTERPRISE DARK MODE SYSTEM & HIGH CONTRAST TYPOGRAPHY
        ============================================= */
        [data-theme="dark"], .dark-mode {
            color-scheme: dark;
            --sb-bg: #0f172a;
            --sb-border: #1e293b;
            --sb-text: #ffffff;
            --sb-text-muted: #94a3b8;
            --sb-hover: #1e293b;
        }
        [data-theme="dark"] body.admin-body,
        body.dark-mode {
            background-color: #0b1120 !important;
            color: #ffffff !important;
        }
        [data-theme="dark"] #content,
        body.dark-mode #content {
            background-color: #0b1120 !important;
            color: #f1f5f9 !important;
        }
        [data-theme="dark"] .top-navbar {
            background-color: #0f172a !important;
            border-bottom-color: #1e293b !important;
        }
        [data-theme="dark"] .sidebar {
            background: #0f172a !important;
            border-right-color: #1e293b !important;
            box-shadow: 2px 0 14px rgba(0, 0, 0, 0.4) !important;
        }
        [data-theme="dark"] .sidebar-header {
            background: #0f172a !important;
            border-bottom-color: #1e293b !important;
        }
        [data-theme="dark"] .sidebar-bottom {
            background: #0f172a !important;
            border-top-color: #1e293b !important;
        }
        [data-theme="dark"] #sidebar .sidebar-nav-link {
            color: #ffffff !important;
        }
        [data-theme="dark"] #sidebar .sidebar-nav-link,
        body.dark-mode #sidebar .sidebar-nav-link {
            color: #ffffff !important;
        }
        [data-theme="dark"] #sidebar .sidebar-nav-link .nav-label,
        body.dark-mode #sidebar .sidebar-nav-link .nav-label {
            color: #ffffff !important;
            font-weight: 600 !important;
        }
        [data-theme="dark"] #sidebar .sidebar-nav-link:hover,
        body.dark-mode #sidebar .sidebar-nav-link:hover {
            background: #1e293b !important;
            border-color: #334155 !important;
        }
        [data-theme="dark"] #sidebar .sidebar-nav-link:hover .nav-label,
        body.dark-mode #sidebar .sidebar-nav-link:hover .nav-label {
            color: #ffffff !important;
        }
        [data-theme="dark"] #sidebar ul.components li.active > a.sidebar-nav-link,
        [data-theme="dark"] #sidebar ul.components li.active > .sidebar-nav-link,
        [data-theme="dark"] #sidebar li.active > .sidebar-nav-link,
        [data-theme="dark"] #sidebar .sidebar-nav-link.active,
        body.dark-mode #sidebar .sidebar-nav-link.active {
            background: rgba(37, 99, 235, 0.22) !important;
            border-color: rgba(59, 130, 246, 0.4) !important;
            border-left: 4px solid #3b82f6 !important;
        }
        [data-theme="dark"] #sidebar ul.components li.active > a.sidebar-nav-link .nav-label,
        [data-theme="dark"] #sidebar .sidebar-nav-link.active .nav-label,
        body.dark-mode #sidebar .sidebar-nav-link.active .nav-label {
            color: #ffffff !important;
            font-weight: 700 !important;
        }

        /* Dark Mode Vivid Category Icon Colors (Ensuring high contrast & vivid saturation) */
        [data-theme="dark"] .nav-icon.nav-icon-dashboard, body.dark-mode .nav-icon.nav-icon-dashboard,
        [data-theme="dark"] .nav-icon.nav-icon-dashboard i, body.dark-mode .nav-icon.nav-icon-dashboard i { color: #818cf8 !important; }

        [data-theme="dark"] .nav-icon.nav-icon-sales, body.dark-mode .nav-icon.nav-icon-sales,
        [data-theme="dark"] .nav-icon.nav-icon-sales i, body.dark-mode .nav-icon.nav-icon-sales i { color: #10b981 !important; }

        [data-theme="dark"] .nav-icon.nav-icon-items, body.dark-mode .nav-icon.nav-icon-items,
        [data-theme="dark"] .nav-icon.nav-icon-items i, body.dark-mode .nav-icon.nav-icon-items i { color: #3b82f6 !important; }

        [data-theme="dark"] .nav-icon.nav-icon-purchases, body.dark-mode .nav-icon.nav-icon-purchases,
        [data-theme="dark"] .nav-icon.nav-icon-purchases i, body.dark-mode .nav-icon.nav-icon-purchases i { color: #a855f7 !important; }

        [data-theme="dark"] .nav-icon.nav-icon-warehouse, body.dark-mode .nav-icon.nav-icon-warehouse,
        [data-theme="dark"] .nav-icon.nav-icon-warehouse i, body.dark-mode .nav-icon.nav-icon-warehouse i { color: #f59e0b !important; }

        [data-theme="dark"] .nav-icon.nav-icon-warranties, body.dark-mode .nav-icon.nav-icon-warranties,
        [data-theme="dark"] .nav-icon.nav-icon-warranties i, body.dark-mode .nav-icon.nav-icon-warranties i { color: #2dd4bf !important; }

        [data-theme="dark"] .nav-icon.nav-icon-petroleum, body.dark-mode .nav-icon.nav-icon-petroleum,
        [data-theme="dark"] .nav-icon.nav-icon-petroleum i, body.dark-mode .nav-icon.nav-icon-petroleum i { color: #fb923c !important; }

        [data-theme="dark"] .nav-icon.nav-icon-station-day, body.dark-mode .nav-icon.nav-icon-station-day,
        [data-theme="dark"] .nav-icon.nav-icon-station-day i, body.dark-mode .nav-icon.nav-icon-station-day i { color: #38bdf8 !important; }

        [data-theme="dark"] .nav-icon.nav-icon-invoices, body.dark-mode .nav-icon.nav-icon-invoices,
        [data-theme="dark"] .nav-icon.nav-icon-invoices i, body.dark-mode .nav-icon.nav-icon-invoices i { color: #818cf8 !important; }

        [data-theme="dark"] .nav-icon.nav-icon-customers, body.dark-mode .nav-icon.nav-icon-customers,
        [data-theme="dark"] .nav-icon.nav-icon-customers i, body.dark-mode .nav-icon.nav-icon-customers i { color: #60a5fa !important; }

        [data-theme="dark"] .nav-icon.nav-icon-station-setup, body.dark-mode .nav-icon.nav-icon-station-setup,
        [data-theme="dark"] .nav-icon.nav-icon-station-setup i, body.dark-mode .nav-icon.nav-icon-station-setup i { color: #94a3b8 !important; }

        [data-theme="dark"] .nav-icon.nav-icon-expenses, body.dark-mode .nav-icon.nav-icon-expenses,
        [data-theme="dark"] .nav-icon.nav-icon-expenses i, body.dark-mode .nav-icon.nav-icon-expenses i { color: #ef4444 !important; }

        [data-theme="dark"] .nav-icon.nav-icon-reports, body.dark-mode .nav-icon.nav-icon-reports,
        [data-theme="dark"] .nav-icon.nav-icon-reports i, body.dark-mode .nav-icon.nav-icon-reports i { color: #0ea5e9 !important; }

        [data-theme="dark"] .nav-icon.nav-icon-branches, body.dark-mode .nav-icon.nav-icon-branches,
        [data-theme="dark"] .nav-icon.nav-icon-branches i, body.dark-mode .nav-icon.nav-icon-branches i { color: #a78bfa !important; }

        [data-theme="dark"] .nav-icon.nav-icon-staff, body.dark-mode .nav-icon.nav-icon-staff,
        [data-theme="dark"] .nav-icon.nav-icon-staff i, body.dark-mode .nav-icon.nav-icon-staff i { color: #06b6d4 !important; }

        [data-theme="dark"] .nav-icon.nav-icon-card, body.dark-mode .nav-icon.nav-icon-card,
        [data-theme="dark"] .nav-icon.nav-icon-card i, body.dark-mode .nav-icon.nav-icon-card i { color: #10b981 !important; }

        [data-theme="dark"] .nav-icon.nav-icon-settings, body.dark-mode .nav-icon.nav-icon-settings,
        [data-theme="dark"] .nav-icon.nav-icon-settings i, body.dark-mode .nav-icon.nav-icon-settings i { color: #cbd5e1 !important; }

        [data-theme="dark"] .nav-icon.nav-icon-plans, body.dark-mode .nav-icon.nav-icon-plans,
        [data-theme="dark"] .nav-icon.nav-icon-plans i, body.dark-mode .nav-icon.nav-icon-plans i { color: #f59e0b !important; }

        [data-theme="dark"] .nav-icon.nav-icon-reviews, body.dark-mode .nav-icon.nav-icon-reviews,
        [data-theme="dark"] .nav-icon.nav-icon-reviews i, body.dark-mode .nav-icon.nav-icon-reviews i { color: #facc15 !important; }

        [data-theme="dark"] .nav-icon.nav-icon-superadmin-shops, body.dark-mode .nav-icon.nav-icon-superadmin-shops,
        [data-theme="dark"] .nav-icon.nav-icon-superadmin-shops i, body.dark-mode .nav-icon.nav-icon-superadmin-shops i { color: #818cf8 !important; }

        [data-theme="dark"] .nav-icon.nav-icon-superadmin-payments, body.dark-mode .nav-icon.nav-icon-superadmin-payments,
        [data-theme="dark"] .nav-icon.nav-icon-superadmin-payments i, body.dark-mode .nav-icon.nav-icon-superadmin-payments i { color: #10b981 !important; }

        [data-theme="dark"] .nav-icon.nav-icon-superadmin-cms, body.dark-mode .nav-icon.nav-icon-superadmin-cms,
        [data-theme="dark"] .nav-icon.nav-icon-superadmin-cms i, body.dark-mode .nav-icon.nav-icon-superadmin-cms i { color: #a78bfa !important; }

        /* =============================================
           ENTERPRISE DARK MODE SYSTEM & HIGH CONTRAST TYPOGRAPHY
        ============================================= */
        [data-theme="dark"], .dark-mode, html.dark-mode {
            color-scheme: dark;
            --sb-bg: #0f172a;
            --sb-border: #1e293b;
            --sb-text: #ffffff;
            --sb-text-muted: #94a3b8;
            --sb-hover: #1e293b;
            --spos-card-bg: #111827 !important;
            --spos-panel-bg: #0b1120 !important;
            --spos-border: #1f2937 !important;
            --spos-dark: #f8fafc !important;
            --spos-primary-light: rgba(37, 99, 235, 0.2) !important;
            --spos-success-light: rgba(5, 150, 105, 0.2) !important;
            --spos-amber-light: rgba(217, 119, 6, 0.2) !important;
            --spos-danger-light: rgba(220, 38, 38, 0.2) !important;
        }

        /* Base & Backgrounds */
        [data-theme="dark"] body.admin-body,
        body.dark-mode,
        html.dark-mode body {
            background-color: #0b1120 !important;
            color: #f1f5f9 !important;
        }
        [data-theme="dark"] #content,
        body.dark-mode #content,
        html.dark-mode #content {
            background-color: #0b1120 !important;
            color: #f1f5f9 !important;
        }
        [data-theme="dark"] .top-navbar,
        body.dark-mode .top-navbar {
            background-color: #0f172a !important;
            border-bottom-color: #1e293b !important;
        }
        [data-theme="dark"] .sidebar,
        body.dark-mode .sidebar {
            background: #0f172a !important;
            border-right-color: #1e293b !important;
            box-shadow: 2px 0 14px rgba(0, 0, 0, 0.4) !important;
        }
        [data-theme="dark"] .sidebar-header,
        body.dark-mode .sidebar-header {
            background: #0f172a !important;
            border-bottom-color: #1e293b !important;
        }
        [data-theme="dark"] .sidebar-bottom,
        body.dark-mode .sidebar-bottom {
            background: #0f172a !important;
            border-top-color: #1e293b !important;
        }

        /* Sidebar Links & Active States */
        [data-theme="dark"] #sidebar .sidebar-nav-link,
        body.dark-mode #sidebar .sidebar-nav-link {
            color: #ffffff !important;
        }
        [data-theme="dark"] #sidebar .sidebar-nav-link .nav-label,
        body.dark-mode #sidebar .sidebar-nav-link .nav-label {
            color: #ffffff !important;
            font-weight: 600 !important;
        }
        [data-theme="dark"] #sidebar .sidebar-nav-link:hover,
        body.dark-mode #sidebar .sidebar-nav-link:hover {
            background: #1e293b !important;
            border-color: #334155 !important;
        }
        [data-theme="dark"] #sidebar .sidebar-nav-link:hover .nav-label,
        body.dark-mode #sidebar .sidebar-nav-link:hover .nav-label {
            color: #ffffff !important;
        }
        [data-theme="dark"] #sidebar ul.components li.active > a.sidebar-nav-link,
        [data-theme="dark"] #sidebar ul.components li.active > .sidebar-nav-link,
        [data-theme="dark"] #sidebar li.active > .sidebar-nav-link,
        [data-theme="dark"] #sidebar .sidebar-nav-link.active,
        body.dark-mode #sidebar .sidebar-nav-link.active {
            background: rgba(37, 99, 235, 0.22) !important;
            border-color: rgba(59, 130, 246, 0.4) !important;
            border-left: 4px solid #3b82f6 !important;
        }
        [data-theme="dark"] #sidebar ul.components li.active > a.sidebar-nav-link .nav-label,
        [data-theme="dark"] #sidebar .sidebar-nav-link.active .nav-label,
        body.dark-mode #sidebar .sidebar-nav-link.active .nav-label {
            color: #ffffff !important;
            font-weight: 700 !important;
        }

        /* Dark Mode Sidebar Dropdowns (Parent Button Never White Box!) */
        [data-theme="dark"] #sidebar .sidebar-nav-link[aria-expanded="true"],
        [data-theme="dark"] #sidebar a.sidebar-nav-link[aria-expanded="true"],
        body.dark-mode #sidebar .sidebar-nav-link[aria-expanded="true"],
        body.dark-mode #sidebar a.sidebar-nav-link[aria-expanded="true"],
        [data-theme="dark"] .custom-dropdown-parent.active-dropdown > .sidebar-nav-link,
        body.dark-mode .custom-dropdown-parent.active-dropdown > .sidebar-nav-link,
        [data-theme="dark"] #sidebar .custom-dropdown-parent.active-dropdown > .sidebar-nav-link,
        body.dark-mode #sidebar .custom-dropdown-parent.active-dropdown > .sidebar-nav-link {
            background: #1e293b !important;
            background-color: #1e293b !important;
            color: #ffffff !important;
            border-color: #334155 !important;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.25) !important;
        }
        [data-theme="dark"] #sidebar .sidebar-nav-link[aria-expanded="true"] .nav-label,
        [data-theme="dark"] #sidebar a.sidebar-nav-link[aria-expanded="true"] .nav-label,
        body.dark-mode #sidebar .sidebar-nav-link[aria-expanded="true"] .nav-label,
        body.dark-mode #sidebar a.sidebar-nav-link[aria-expanded="true"] .nav-label,
        [data-theme="dark"] .custom-dropdown-parent.active-dropdown > .sidebar-nav-link .nav-label,
        body.dark-mode .custom-dropdown-parent.active-dropdown > .sidebar-nav-link .nav-label {
            color: #ffffff !important;
            font-weight: 700 !important;
        }
        [data-theme="dark"] #sidebar .sidebar-nav-link[aria-expanded="true"] .menu-chevron,
        body.dark-mode #sidebar .sidebar-nav-link[aria-expanded="true"] .menu-chevron {
            transform: rotate(180deg) !important;
            color: #38bdf8 !important;
            background: #0f172a !important;
        }

        /* Dark Mode Submenu Container & Links (Pure Transparent, Never White Box!) */
        [data-theme="dark"] #sidebar .custom-submenu,
        [data-theme="dark"] #sidebar ul.custom-submenu,
        [data-theme="dark"] #sidebar ul.collapse,
        [data-theme="dark"] #sidebar ul.collapsing,
        [data-theme="dark"] #sidebar .collapse.show,
        [data-theme="dark"] #sidebar div.custom-dropdown-parent ul,
        body.dark-mode #sidebar .custom-submenu,
        body.dark-mode #sidebar ul.custom-submenu,
        body.dark-mode #sidebar ul.collapse,
        body.dark-mode #sidebar ul.collapsing,
        body.dark-mode #sidebar .collapse.show,
        body.dark-mode #sidebar div.custom-dropdown-parent ul {
            background: transparent !important;
            background-color: transparent !important;
            box-shadow: none !important;
            border-left: 2px solid #334155 !important;
        }
        [data-theme="dark"] #sidebar .custom-submenu li,
        body.dark-mode #sidebar .custom-submenu li {
            background: transparent !important;
            background-color: transparent !important;
        }
        [data-theme="dark"] #sidebar .custom-submenu li a,
        body.dark-mode #sidebar .custom-submenu li a {
            color: #cbd5e1 !important;
            background: transparent !important;
            background-color: transparent !important;
            font-weight: 600 !important;
        }
        [data-theme="dark"] #sidebar .custom-submenu li a::before,
        body.dark-mode #sidebar .custom-submenu li a::before {
            color: #64748b !important;
        }
        [data-theme="dark"] #sidebar .custom-submenu li a:hover,
        body.dark-mode #sidebar .custom-submenu li a:hover {
            color: #ffffff !important;
            background: #1e293b !important;
            background-color: #1e293b !important;
        }
        [data-theme="dark"] #sidebar .custom-submenu li a:hover::before,
        body.dark-mode #sidebar .custom-submenu li a:hover::before {
            color: #38bdf8 !important;
            transform: translateY(-50%) scale(1.3) !important;
        }
        [data-theme="dark"] #sidebar .custom-submenu li a.active,
        body.dark-mode #sidebar .custom-submenu li a.active {
            color: #60a5fa !important;
            background: rgba(37, 99, 235, 0.22) !important;
            background-color: rgba(37, 99, 235, 0.22) !important;
            border: 1px solid rgba(59, 130, 246, 0.4) !important;
            font-weight: 700 !important;
        }
        [data-theme="dark"] #sidebar .custom-submenu li a.active::before,
        body.dark-mode #sidebar .custom-submenu li a.active::before {
            color: #60a5fa !important;
            transform: translateY(-50%) scale(1.3) !important;
        }
        [data-theme="dark"] .menu-chevron,
        body.dark-mode .menu-chevron {
            background: #1e293b !important;
            color: #ffffff !important;
        }

        /* Vivid Dark Mode Category Icons */
        [data-theme="dark"] .nav-icon.nav-icon-dashboard, body.dark-mode .nav-icon.nav-icon-dashboard,
        [data-theme="dark"] .nav-icon.nav-icon-dashboard i, body.dark-mode .nav-icon.nav-icon-dashboard i { color: #818cf8 !important; }
        [data-theme="dark"] .nav-icon.nav-icon-sales, body.dark-mode .nav-icon.nav-icon-sales,
        [data-theme="dark"] .nav-icon.nav-icon-sales i, body.dark-mode .nav-icon.nav-icon-sales i { color: #10b981 !important; }
        [data-theme="dark"] .nav-icon.nav-icon-items, body.dark-mode .nav-icon.nav-icon-items,
        [data-theme="dark"] .nav-icon.nav-icon-items i, body.dark-mode .nav-icon.nav-icon-items i { color: #3b82f6 !important; }
        [data-theme="dark"] .nav-icon.nav-icon-purchases, body.dark-mode .nav-icon.nav-icon-purchases,
        [data-theme="dark"] .nav-icon.nav-icon-purchases i, body.dark-mode .nav-icon.nav-icon-purchases i { color: #a855f7 !important; }
        [data-theme="dark"] .nav-icon.nav-icon-warehouse, body.dark-mode .nav-icon.nav-icon-warehouse,
        [data-theme="dark"] .nav-icon.nav-icon-warehouse i, body.dark-mode .nav-icon.nav-icon-warehouse i { color: #f59e0b !important; }
        [data-theme="dark"] .nav-icon.nav-icon-warranties, body.dark-mode .nav-icon.nav-icon-warranties,
        [data-theme="dark"] .nav-icon.nav-icon-warranties i, body.dark-mode .nav-icon.nav-icon-warranties i { color: #2dd4bf !important; }
        [data-theme="dark"] .nav-icon.nav-icon-petroleum, body.dark-mode .nav-icon.nav-icon-petroleum,
        [data-theme="dark"] .nav-icon.nav-icon-petroleum i, body.dark-mode .nav-icon.nav-icon-petroleum i { color: #fb923c !important; }
        [data-theme="dark"] .nav-icon.nav-icon-station-day, body.dark-mode .nav-icon.nav-icon-station-day,
        [data-theme="dark"] .nav-icon.nav-icon-station-day i, body.dark-mode .nav-icon.nav-icon-station-day i { color: #38bdf8 !important; }
        [data-theme="dark"] .nav-icon.nav-icon-invoices, body.dark-mode .nav-icon.nav-icon-invoices,
        [data-theme="dark"] .nav-icon.nav-icon-invoices i, body.dark-mode .nav-icon.nav-icon-invoices i { color: #818cf8 !important; }
        [data-theme="dark"] .nav-icon.nav-icon-customers, body.dark-mode .nav-icon.nav-icon-customers,
        [data-theme="dark"] .nav-icon.nav-icon-customers i, body.dark-mode .nav-icon.nav-icon-customers i { color: #60a5fa !important; }
        [data-theme="dark"] .nav-icon.nav-icon-station-setup, body.dark-mode .nav-icon.nav-icon-station-setup,
        [data-theme="dark"] .nav-icon.nav-icon-station-setup i, body.dark-mode .nav-icon.nav-icon-station-setup i { color: #94a3b8 !important; }
        [data-theme="dark"] .nav-icon.nav-icon-expenses, body.dark-mode .nav-icon.nav-icon-expenses,
        [data-theme="dark"] .nav-icon.nav-icon-expenses i, body.dark-mode .nav-icon.nav-icon-expenses i { color: #ef4444 !important; }
        [data-theme="dark"] .nav-icon.nav-icon-reports, body.dark-mode .nav-icon.nav-icon-reports,
        [data-theme="dark"] .nav-icon.nav-icon-reports i, body.dark-mode .nav-icon.nav-icon-reports i { color: #0ea5e9 !important; }
        [data-theme="dark"] .nav-icon.nav-icon-branches, body.dark-mode .nav-icon.nav-icon-branches,
        [data-theme="dark"] .nav-icon.nav-icon-branches i, body.dark-mode .nav-icon.nav-icon-branches i { color: #a78bfa !important; }
        [data-theme="dark"] .nav-icon.nav-icon-staff, body.dark-mode .nav-icon.nav-icon-staff,
        [data-theme="dark"] .nav-icon.nav-icon-staff i, body.dark-mode .nav-icon.nav-icon-staff i { color: #06b6d4 !important; }
        [data-theme="dark"] .nav-icon.nav-icon-card, body.dark-mode .nav-icon.nav-icon-card,
        [data-theme="dark"] .nav-icon.nav-icon-card i, body.dark-mode .nav-icon.nav-icon-card i { color: #10b981 !important; }
        [data-theme="dark"] .nav-icon.nav-icon-settings, body.dark-mode .nav-icon.nav-icon-settings,
        [data-theme="dark"] .nav-icon.nav-icon-settings i, body.dark-mode .nav-icon.nav-icon-settings i { color: #cbd5e1 !important; }
        [data-theme="dark"] .nav-icon.nav-icon-plans, body.dark-mode .nav-icon.nav-icon-plans,
        [data-theme="dark"] .nav-icon.nav-icon-plans i, body.dark-mode .nav-icon.nav-icon-plans i { color: #f59e0b !important; }
        [data-theme="dark"] .nav-icon.nav-icon-reviews, body.dark-mode .nav-icon.nav-icon-reviews,
        [data-theme="dark"] .nav-icon.nav-icon-reviews i, body.dark-mode .nav-icon.nav-icon-reviews i { color: #facc15 !important; }
        [data-theme="dark"] .nav-icon.nav-icon-superadmin-shops, body.dark-mode .nav-icon.nav-icon-superadmin-shops,
        [data-theme="dark"] .nav-icon.nav-icon-superadmin-shops i, body.dark-mode .nav-icon.nav-icon-superadmin-shops i { color: #818cf8 !important; }
        [data-theme="dark"] .nav-icon.nav-icon-superadmin-payments, body.dark-mode .nav-icon.nav-icon-superadmin-payments,
        [data-theme="dark"] .nav-icon.nav-icon-superadmin-payments i, body.dark-mode .nav-icon.nav-icon-superadmin-payments i { color: #10b981 !important; }
        [data-theme="dark"] .nav-icon.nav-icon-superadmin-cms, body.dark-mode .nav-icon.nav-icon-superadmin-cms,
        [data-theme="dark"] .nav-icon.nav-icon-superadmin-cms i, body.dark-mode .nav-icon.nav-icon-superadmin-cms i { color: #a78bfa !important; }

        /* System-Wide Cards, Containers & Modals */
        [data-theme="dark"] .card,
        [data-theme="dark"] .dash-card,
        [data-theme="dark"] .exec-header,
        [data-theme="dark"] .metric-card,
        [data-theme="dark"] .quick-op-btn:not(.primary-pos),
        [data-theme="dark"] .table-card,
        [data-theme="dark"] .filter-banner-strip,
        [data-theme="dark"] .dropdown-menu,
        [data-theme="dark"] .admin-card,
        [data-theme="dark"] .modal-content,
        body.dark-mode .card,
        body.dark-mode .admin-card,
        body.dark-mode .modal-content,
        body.dark-mode .dropdown-menu {
            background-color: #111827 !important;
            border-color: #1f2937 !important;
            color: #f1f5f9 !important;
        }

        [data-theme="dark"] .card-header,
        [data-theme="dark"] .card-header.bg-white,
        [data-theme="dark"] .modal-header,
        body.dark-mode .card-header,
        body.dark-mode .card-header.bg-white,
        body.dark-mode .modal-header {
            background-color: #111827 !important;
            border-bottom-color: #1f2937 !important;
            color: #ffffff !important;
        }

        [data-theme="dark"] .card-body,
        [data-theme="dark"] .modal-body,
        body.dark-mode .card-body,
        body.dark-mode .modal-body {
            background-color: transparent !important;
            color: #f1f5f9 !important;
        }

        [data-theme="dark"] .card-footer,
        [data-theme="dark"] .card-footer.bg-white,
        [data-theme="dark"] .modal-footer,
        body.dark-mode .card-footer,
        body.dark-mode .card-footer.bg-white,
        body.dark-mode .modal-footer {
            background-color: #111827 !important;
            border-top-color: #1f2937 !important;
            color: #cbd5e1 !important;
        }

        [data-theme="dark"] .dropdown-item,
        body.dark-mode .dropdown-item {
            color: #e2e8f0 !important;
        }
        [data-theme="dark"] .dropdown-item:hover,
        [data-theme="dark"] .dropdown-item:focus,
        body.dark-mode .dropdown-item:hover,
        body.dark-mode .dropdown-item:focus {
            background-color: #1f2937 !important;
            color: #ffffff !important;
        }

        /* Form Controls, Inputs & Search Bars */
        [data-theme="dark"] .form-control,
        [data-theme="dark"] .form-select,
        [data-theme="dark"] .exec-select,
        [data-theme="dark"] .form-control.bg-light,
        [data-theme="dark"] .form-select.bg-light,
        [data-theme="dark"] .form-control.bg-white,
        [data-theme="dark"] .form-select.bg-white,
        [data-theme="dark"] input.bg-light,
        [data-theme="dark"] select.bg-light,
        [data-theme="dark"] textarea.bg-light,
        [data-theme="dark"] input.bg-white,
        [data-theme="dark"] select.bg-white,
        [data-theme="dark"] textarea.bg-white,
        body.dark-mode .form-control,
        body.dark-mode .form-select,
        body.dark-mode .exec-select,
        body.dark-mode .form-control.bg-light,
        body.dark-mode .form-select.bg-light,
        body.dark-mode .form-control.bg-white,
        body.dark-mode .form-select.bg-white,
        body.dark-mode input.bg-light,
        body.dark-mode select.bg-light,
        body.dark-mode textarea.bg-light,
        body.dark-mode input.bg-white,
        body.dark-mode select.bg-white,
        body.dark-mode textarea.bg-white {
            background-color: #111827 !important;
            border-color: #374151 !important;
            color: #ffffff !important;
            opacity: 1 !important;
        }
        [data-theme="dark"] .form-control:focus,
        [data-theme="dark"] .form-select:focus,
        [data-theme="dark"] .exec-select:focus,
        body.dark-mode .form-control:focus,
        body.dark-mode .form-select:focus,
        body.dark-mode .exec-select:focus {
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2) !important;
            background-color: #111827 !important;
            color: #ffffff !important;
        }
        [data-theme="dark"] .form-control:read-only,
        [data-theme="dark"] .form-control:disabled,
        [data-theme="dark"] .form-select:disabled,
        body.dark-mode .form-control:read-only,
        body.dark-mode .form-control:disabled,
        body.dark-mode .form-select:disabled {
            background-color: #0f172a !important;
            border-color: #1f2937 !important;
            color: #94a3b8 !important;
        }
        [data-theme="dark"] input::placeholder,
        [data-theme="dark"] textarea::placeholder,
        [data-theme="dark"] .form-control::placeholder,
        body.dark-mode input::placeholder,
        body.dark-mode textarea::placeholder,
        body.dark-mode .form-control::placeholder {
            color: #94a3b8 !important;
            opacity: 1 !important;
        }
        [data-theme="dark"] select option,
        [data-theme="dark"] .form-select option,
        body.dark-mode select option,
        body.dark-mode .form-select option {
            background-color: #111827 !important;
            color: #ffffff !important;
        }
        [data-theme="dark"] .form-check-input,
        body.dark-mode .form-check-input {
            background-color: #1e293b !important;
            border-color: #475569 !important;
        }
        [data-theme="dark"] .form-check-input:checked,
        body.dark-mode .form-check-input:checked {
            background-color: #2563eb !important;
            border-color: #2563eb !important;
        }
        [data-theme="dark"] .input-group-text,
        [data-theme="dark"] .input-group-text.bg-light,
        [data-theme="dark"] .input-group-text.bg-white,
        body.dark-mode .input-group-text,
        body.dark-mode .input-group-text.bg-light,
        body.dark-mode .input-group-text.bg-white {
            background-color: #1e293b !important;
            border-color: #374151 !important;
            color: #cbd5e1 !important;
        }
        [data-theme="dark"] .custom-search-bar,
        body.dark-mode .custom-search-bar {
            background-color: #111827 !important;
            border-color: #374151 !important;
        }
        [data-theme="dark"] .custom-search-bar input,
        body.dark-mode .custom-search-bar input {
            color: #ffffff !important;
        }
        [data-theme="dark"] .custom-search-bar input::placeholder,
        body.dark-mode .custom-search-bar input::placeholder {
            color: #94a3b8 !important;
            opacity: 1 !important;
        }

        /* All Light Background Classes in Dark Mode */
        [data-theme="dark"] .bg-white,
        body.dark-mode .bg-white {
            background-color: #111827 !important;
            color: #f1f5f9 !important;
        }
        [data-theme="dark"] .bg-light,
        [data-theme="dark"] thead.bg-light,
        [data-theme="dark"] .table-light,
        [data-theme="dark"] thead.table-light,
        [data-theme="dark"] .table-light > * > *,
        body.dark-mode .bg-light,
        body.dark-mode thead.bg-light,
        body.dark-mode .table-light,
        body.dark-mode thead.table-light,
        body.dark-mode .table-light > * > * {
            background-color: #0f172a !important;
            color: #cbd5e1 !important;
            border-color: #1f2937 !important;
        }

        /* Master Attribute Overrides for Hardcoded Inline Styles */
        [data-theme="dark"] [style*="background:#fff"],
        [data-theme="dark"] [style*="background: #fff"],
        [data-theme="dark"] [style*="background:#ffffff"],
        [data-theme="dark"] [style*="background: #ffffff"],
        [data-theme="dark"] [style*="background-color:#fff"],
        [data-theme="dark"] [style*="background-color: #fff"],
        [data-theme="dark"] [style*="background-color:#ffffff"],
        [data-theme="dark"] [style*="background-color: #ffffff"],
        [data-theme="dark"] [style*="background: white"],
        [data-theme="dark"] [style*="background-color: white"],
        body.dark-mode [style*="background:#fff"],
        body.dark-mode [style*="background: #fff"],
        body.dark-mode [style*="background:#ffffff"],
        body.dark-mode [style*="background: #ffffff"],
        body.dark-mode [style*="background-color:#fff"],
        body.dark-mode [style*="background-color: #fff"],
        body.dark-mode [style*="background-color:#ffffff"],
        body.dark-mode [style*="background-color: #ffffff"],
        body.dark-mode [style*="background: white"],
        body.dark-mode [style*="background-color: white"] {
            background-color: #111827 !important;
            border-color: #1f2937 !important;
        }

        [data-theme="dark"] [style*="background:#f8fafc"],
        [data-theme="dark"] [style*="background: #f8fafc"],
        [data-theme="dark"] [style*="background-color:#f8fafc"],
        [data-theme="dark"] [style*="background-color: #f8fafc"],
        [data-theme="dark"] [style*="background:#f1f5f9"],
        [data-theme="dark"] [style*="background: #f1f5f9"],
        [data-theme="dark"] [style*="background-color:#f1f5f9"],
        [data-theme="dark"] [style*="background-color: #f1f5f9"],
        [data-theme="dark"] [style*="background:#fafafa"],
        [data-theme="dark"] [style*="background: #fafafa"],
        [data-theme="dark"] [style*="background-color:#fafafa"],
        [data-theme="dark"] [style*="background-color: #fafafa"],
        [data-theme="dark"] [style*="background:#f9fafb"],
        [data-theme="dark"] [style*="background: #f9fafb"],
        [data-theme="dark"] [style*="background-color:#f9fafb"],
        [data-theme="dark"] [style*="background-color: #f9fafb"],
        [data-theme="dark"] [style*="background:#e9ecef"],
        [data-theme="dark"] [style*="background: #e9ecef"],
        [data-theme="dark"] [style*="background-color:#e9ecef"],
        [data-theme="dark"] [style*="background-color: #e9ecef"],
        body.dark-mode [style*="background:#f8fafc"],
        body.dark-mode [style*="background: #f8fafc"],
        body.dark-mode [style*="background-color:#f8fafc"],
        body.dark-mode [style*="background-color: #f8fafc"],
        body.dark-mode [style*="background:#f1f5f9"],
        body.dark-mode [style*="background: #f1f5f9"],
        body.dark-mode [style*="background-color:#f1f5f9"],
        body.dark-mode [style*="background-color: #f1f5f9"],
        body.dark-mode [style*="background:#fafafa"],
        body.dark-mode [style*="background: #fafafa"],
        body.dark-mode [style*="background-color:#fafafa"],
        body.dark-mode [style*="background-color: #fafafa"],
        body.dark-mode [style*="background:#f9fafb"],
        body.dark-mode [style*="background: #f9fafb"],
        body.dark-mode [style*="background-color:#f9fafb"],
        body.dark-mode [style*="background-color: #f9fafb"],
        body.dark-mode [style*="background:#e9ecef"],
        body.dark-mode [style*="background: #e9ecef"],
        body.dark-mode [style*="background-color:#e9ecef"],
        body.dark-mode [style*="background-color: #e9ecef"] {
            background-color: #1e293b !important;
            border-color: #334155 !important;
        }

        [data-theme="dark"] [style*="color:#0f172a"],
        [data-theme="dark"] [style*="color: #0f172a"],
        [data-theme="dark"] [style*="color:#1e293b"],
        [data-theme="dark"] [style*="color: #1e293b"],
        [data-theme="dark"] [style*="color:#334155"],
        [data-theme="dark"] [style*="color: #334155"],
        [data-theme="dark"] [style*="color:#374151"],
        [data-theme="dark"] [style*="color: #374151"],
        [data-theme="dark"] [style*="color:#111827"],
        [data-theme="dark"] [style*="color: #111827"],
        [data-theme="dark"] [style*="color:#1f2937"],
        [data-theme="dark"] [style*="color: #1f2937"],
        [data-theme="dark"] [style*="color:#212529"],
        [data-theme="dark"] [style*="color: #212529"],
        [data-theme="dark"] [style*="color:#000000"],
        [data-theme="dark"] [style*="color: #000000"],
        [data-theme="dark"] [style*="color:#000"],
        [data-theme="dark"] [style*="color: #000"],
        [data-theme="dark"] [style*="color:black"],
        [data-theme="dark"] [style*="color: black"],
        body.dark-mode [style*="color:#0f172a"],
        body.dark-mode [style*="color: #0f172a"],
        body.dark-mode [style*="color:#1e293b"],
        body.dark-mode [style*="color: #1e293b"],
        body.dark-mode [style*="color:#334155"],
        body.dark-mode [style*="color: #334155"],
        body.dark-mode [style*="color:#374151"],
        body.dark-mode [style*="color: #374151"],
        body.dark-mode [style*="color:#111827"],
        body.dark-mode [style*="color: #111827"],
        body.dark-mode [style*="color:#1f2937"],
        body.dark-mode [style*="color: #1f2937"],
        body.dark-mode [style*="color:#212529"],
        body.dark-mode [style*="color: #212529"],
        body.dark-mode [style*="color:#000000"],
        body.dark-mode [style*="color: #000000"],
        body.dark-mode [style*="color:#000"],
        body.dark-mode [style*="color: #000"],
        body.dark-mode [style*="color:black"],
        body.dark-mode [style*="color: black"] {
            color: #ffffff !important;
            opacity: 1 !important;
        }

        [data-theme="dark"] [style*="color:#475569"],
        [data-theme="dark"] [style*="color: #475569"],
        [data-theme="dark"] [style*="color:#64748b"],
        [data-theme="dark"] [style*="color: #64748b"],
        [data-theme="dark"] [style*="color:#495057"],
        [data-theme="dark"] [style*="color: #495057"],
        body.dark-mode [style*="color:#475569"],
        body.dark-mode [style*="color: #475569"],
        body.dark-mode [style*="color:#64748b"],
        body.dark-mode [style*="color: #64748b"],
        body.dark-mode [style*="color:#495057"],
        body.dark-mode [style*="color: #495057"] {
            color: #cbd5e1 !important;
            opacity: 1 !important;
        }

        /* Master Icon Visibility Engine in Dark Mode (No Hidden or Vanishing Icons!) */
        [data-theme="dark"] i.bi,
        [data-theme="dark"] i.fa,
        [data-theme="dark"] i.fas,
        [data-theme="dark"] i.far,
        [data-theme="dark"] .bi,
        body.dark-mode i.bi,
        body.dark-mode i.fa,
        body.dark-mode i.fas,
        body.dark-mode i.far,
        body.dark-mode .bi {
            opacity: 1 !important;
            display: inline-block;
        }

        [data-theme="dark"] i.bi.text-dark,
        [data-theme="dark"] .text-dark i.bi,
        [data-theme="dark"] .text-dark > i.bi,
        [data-theme="dark"] i.text-dark,
        body.dark-mode i.bi.text-dark,
        body.dark-mode .text-dark i.bi,
        body.dark-mode .text-dark > i.bi,
        body.dark-mode i.text-dark {
            color: #f8fafc !important;
        }

        [data-theme="dark"] i.bi.text-secondary,
        [data-theme="dark"] .text-secondary i.bi,
        [data-theme="dark"] i.text-secondary,
        body.dark-mode i.bi.text-secondary,
        body.dark-mode .text-secondary i.bi,
        body.dark-mode i.text-secondary {
            color: #cbd5e1 !important;
        }

        [data-theme="dark"] i.bi.text-muted,
        [data-theme="dark"] .text-muted i.bi,
        body.dark-mode i.bi.text-muted,
        body.dark-mode .text-muted i.bi {
            color: #94a3b8 !important;
        }

        [data-theme="dark"] .text-slate-300,
        body.dark-mode .text-slate-300 {
            color: #94a3b8 !important;
        }

        [data-theme="dark"] .btn-light i.bi,
        [data-theme="dark"] .btn-outline-secondary i.bi,
        body.dark-mode .btn-light i.bi,
        body.dark-mode .btn-outline-secondary i.bi {
            color: #f1f5f9 !important;
        }

        /* High Contrast Table System */
        [data-theme="dark"] .table,
        [data-theme="dark"] .exec-table,
        body.dark-mode .table,
        body.dark-mode .exec-table {
            color: #f1f5f9 !important;
            border-color: #1f2937 !important;
        }
        [data-theme="dark"] .table thead th,
        [data-theme="dark"] .exec-table th,
        [data-theme="dark"] .table-admin th,
        [data-theme="dark"] thead th,
        body.dark-mode .table thead th,
        body.dark-mode .exec-table th,
        body.dark-mode .table-admin th,
        body.dark-mode thead th {
            background-color: #0f172a !important;
            color: #cbd5e1 !important;
            border-bottom: 2px solid #1f2937 !important;
            border-top: none !important;
            font-weight: 700 !important;
        }
        [data-theme="dark"] .table tbody td,
        [data-theme="dark"] .table tfoot td,
        [data-theme="dark"] .table tfoot th,
        [data-theme="dark"] .table tfoot,
        [data-theme="dark"] tfoot,
        [data-theme="dark"] tfoot tr,
        [data-theme="dark"] tfoot td,
        [data-theme="dark"] tfoot th,
        [data-theme="dark"] .exec-table td,
        [data-theme="dark"] .table-admin td,
        body.dark-mode .table tbody td,
        body.dark-mode .table tfoot td,
        body.dark-mode .table tfoot th,
        body.dark-mode .table tfoot,
        body.dark-mode tfoot,
        body.dark-mode tfoot tr,
        body.dark-mode tfoot td,
        body.dark-mode tfoot th,
        body.dark-mode .exec-table td,
        body.dark-mode .table-admin td {
            background-color: transparent !important;
            border-bottom-color: #1f2937 !important;
            color: #f1f5f9 !important;
        }
        [data-theme="dark"] .table-hover > tbody > tr:hover > *,
        [data-theme="dark"] .exec-table tbody tr:hover td,
        body.dark-mode .table-hover > tbody > tr:hover > *,
        body.dark-mode .exec-table tbody tr:hover td {
            background-color: #1e293b !important;
            color: #ffffff !important;
        }
        [data-theme="dark"] .table-striped > tbody > tr:nth-of-type(odd) > *,
        body.dark-mode .table-striped > tbody > tr:nth-of-type(odd) > * {
            background-color: rgba(255, 255, 255, 0.02) !important;
            color: #f1f5f9 !important;
        }

        /* High Contrast Typography Engine (No More Transparent Text!) */
        [data-theme="dark"] .text-dark,
        body.dark-mode .text-dark,
        [data-theme="dark"] strong.text-dark,
        body.dark-mode strong.text-dark,
        [data-theme="dark"] span.text-dark,
        body.dark-mode span.text-dark,
        [data-theme="dark"] h1.text-dark, [data-theme="dark"] h2.text-dark, [data-theme="dark"] h3.text-dark,
        [data-theme="dark"] h4.text-dark, [data-theme="dark"] h5.text-dark, [data-theme="dark"] h6.text-dark,
        body.dark-mode h1.text-dark, body.dark-mode h2.text-dark, body.dark-mode h3.text-dark,
        body.dark-mode h4.text-dark, body.dark-mode h5.text-dark, body.dark-mode h6.text-dark,
        [data-theme="dark"] label.text-dark,
        body.dark-mode label.text-dark,
        [data-theme="dark"] h1, [data-theme="dark"] h2, [data-theme="dark"] h3,
        [data-theme="dark"] h4, [data-theme="dark"] h5, [data-theme="dark"] h6,
        body.dark-mode h1, body.dark-mode h2, body.dark-mode h3,
        body.dark-mode h4, body.dark-mode h5, body.dark-mode h6 { 
            color: #ffffff !important; 
            opacity: 1 !important;
        }
        [data-theme="dark"] .text-muted,
        body.dark-mode .text-muted,
        [data-theme="dark"] small.text-muted,
        body.dark-mode small.text-muted,
        [data-theme="dark"] .text-secondary,
        body.dark-mode .text-secondary,
        [data-theme="dark"] .text-body-secondary,
        body.dark-mode .text-body-secondary,
        [data-theme="dark"] .text-black-50,
        body.dark-mode .text-black-50,
        [data-theme="dark"] small,
        body.dark-mode small,
        [data-theme="dark"] .small,
        body.dark-mode .small { 
            color: #94a3b8 !important; 
            opacity: 1 !important;
        }
        [data-theme="dark"] .text-warning-emphasis,
        body.dark-mode .text-warning-emphasis {
            color: #fbbf24 !important;
        }
        [data-theme="dark"] .text-success-emphasis,
        body.dark-mode .text-success-emphasis {
            color: #4ade80 !important;
        }
        [data-theme="dark"] .text-danger-emphasis,
        body.dark-mode .text-danger-emphasis {
            color: #f87171 !important;
        }
        [data-theme="dark"] .text-primary-emphasis,
        body.dark-mode .text-primary-emphasis {
            color: #60a5fa !important;
        }
        [data-theme="dark"] .text-info-emphasis,
        body.dark-mode .text-info-emphasis {
            color: #38bdf8 !important;
        }
        [data-theme="dark"] .text-body,
        body.dark-mode .text-body,
        [data-theme="dark"] p,
        body.dark-mode p,
        [data-theme="dark"] label,
        body.dark-mode label,
        [data-theme="dark"] .form-label,
        body.dark-mode .form-label,
        [data-theme="dark"] .form-check-label,
        body.dark-mode .form-check-label {
            color: #e2e8f0 !important;
        }

        /* Badges, Buttons, Select2, Offcanvas & Modals in Dark Mode */
        [data-theme="dark"] .badge.bg-light,
        [data-theme="dark"] .badge.bg-light.text-dark,
        body.dark-mode .badge.bg-light,
        body.dark-mode .badge.bg-light.text-dark {
            background-color: #1e293b !important;
            color: #e2e8f0 !important;
            border-color: #334155 !important;
        }
        /* High Contrast Dark Mode Button System (Solid Defined Boxes for All Buttons) */
        [data-theme="dark"] .btn,
        body.dark-mode .btn {
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.25) !important;
        }

        [data-theme="dark"] .btn-primary,
        body.dark-mode .btn-primary {
            background: #2563eb !important;
            background-color: #2563eb !important;
            border: 1.5px solid #3b82f6 !important;
            color: #ffffff !important;
        }
        [data-theme="dark"] .btn-primary:hover,
        body.dark-mode .btn-primary:hover {
            background: #1d4ed8 !important;
            background-color: #1d4ed8 !important;
            border-color: #60a5fa !important;
            color: #ffffff !important;
        }
        [data-theme="dark"] .btn-primary:disabled,
        [data-theme="dark"] .btn-primary.disabled,
        body.dark-mode .btn-primary:disabled,
        body.dark-mode .btn-primary.disabled {
            background-color: #1e293b !important;
            background: #1e293b !important;
            border: 1.5px solid #334155 !important;
            color: #94a3b8 !important;
            opacity: 1 !important;
            box-shadow: none !important;
        }

        [data-theme="dark"] .btn-outline-primary,
        body.dark-mode .btn-outline-primary {
            background-color: #1e293b !important;
            border: 1.5px solid #3b82f6 !important;
            color: #60a5fa !important;
        }
        [data-theme="dark"] .btn-outline-primary:hover,
        body.dark-mode .btn-outline-primary:hover {
            background-color: #2563eb !important;
            border-color: #3b82f6 !important;
            color: #ffffff !important;
        }
        [data-theme="dark"] .btn-outline-primary:disabled,
        [data-theme="dark"] .btn-outline-primary.disabled,
        body.dark-mode .btn-outline-primary:disabled,
        body.dark-mode .btn-outline-primary.disabled {
            background-color: #1e293b !important;
            border: 1.5px solid #334155 !important;
            color: #64748b !important;
            opacity: 1 !important;
        }

        [data-theme="dark"] .btn-secondary,
        body.dark-mode .btn-secondary {
            background-color: #334155 !important;
            border: 1.5px solid #475569 !important;
            color: #ffffff !important;
        }

        [data-theme="dark"] .btn-outline-secondary,
        body.dark-mode .btn-outline-secondary {
            background-color: #1e293b !important;
            border: 1.5px solid #475569 !important;
            color: #cbd5e1 !important;
        }
        [data-theme="dark"] .btn-outline-secondary:hover,
        body.dark-mode .btn-outline-secondary:hover {
            background-color: #334155 !important;
            border-color: #64748b !important;
            color: #ffffff !important;
        }
        [data-theme="dark"] .btn-outline-secondary:disabled,
        [data-theme="dark"] .btn-outline-secondary.disabled,
        body.dark-mode .btn-outline-secondary:disabled,
        body.dark-mode .btn-outline-secondary.disabled {
            background-color: #1e293b !important;
            border: 1.5px solid #334155 !important;
            color: #64748b !important;
            opacity: 1 !important;
        }

        [data-theme="dark"] .btn-light,
        body.dark-mode .btn-light {
            background-color: #1e293b !important;
            border: 1.5px solid #334155 !important;
            color: #ffffff !important;
        }
        [data-theme="dark"] .btn-light:hover,
        body.dark-mode .btn-light:hover {
            background-color: #334155 !important;
            border-color: #475569 !important;
            color: #ffffff !important;
        }

        [data-theme="dark"] .btn-dark,
        body.dark-mode .btn-dark {
            background-color: #1e293b !important;
            border: 1.5px solid #475569 !important;
            color: #ffffff !important;
        }
        [data-theme="dark"] .btn-dark:hover,
        body.dark-mode .btn-dark:hover {
            background-color: #334155 !important;
            border-color: #64748b !important;
            color: #ffffff !important;
        }

        [data-theme="dark"] .btn-success,
        body.dark-mode .btn-success {
            background-color: #059669 !important;
            border: 1.5px solid #10b981 !important;
            color: #ffffff !important;
        }
        [data-theme="dark"] .btn-success:hover,
        body.dark-mode .btn-success:hover {
            background-color: #047857 !important;
            border-color: #34d399 !important;
            color: #ffffff !important;
        }
        [data-theme="dark"] .btn-outline-success,
        body.dark-mode .btn-outline-success {
            background-color: #1e293b !important;
            border: 1.5px solid #10b981 !important;
            color: #34d399 !important;
        }
        [data-theme="dark"] .btn-outline-success:hover,
        body.dark-mode .btn-outline-success:hover {
            background-color: #059669 !important;
            border-color: #10b981 !important;
            color: #ffffff !important;
        }

        [data-theme="dark"] .btn-danger,
        body.dark-mode .btn-danger {
            background-color: #dc2626 !important;
            border: 1.5px solid #ef4444 !important;
            color: #ffffff !important;
        }
        [data-theme="dark"] .btn-danger:hover,
        body.dark-mode .btn-danger:hover {
            background-color: #b91c1c !important;
            border-color: #f87171 !important;
            color: #ffffff !important;
        }
        [data-theme="dark"] .btn-outline-danger,
        body.dark-mode .btn-outline-danger {
            background-color: #1e293b !important;
            border: 1.5px solid #ef4444 !important;
            color: #f87171 !important;
        }
        [data-theme="dark"] .btn-outline-danger:hover,
        body.dark-mode .btn-outline-danger:hover {
            background-color: #dc2626 !important;
            border-color: #ef4444 !important;
            color: #ffffff !important;
        }

        [data-theme="dark"] .btn-warning,
        body.dark-mode .btn-warning {
            background-color: #d97706 !important;
            border: 1.5px solid #f59e0b !important;
            color: #ffffff !important;
        }
        [data-theme="dark"] .btn-outline-warning,
        body.dark-mode .btn-outline-warning {
            background-color: #1e293b !important;
            border: 1.5px solid #f59e0b !important;
            color: #fbbf24 !important;
        }

        /* Staff & General Opacity Badges */
        [data-theme="dark"] .bg-primary.bg-opacity-10,
        body.dark-mode .bg-primary.bg-opacity-10 {
            background-color: rgba(37, 99, 235, 0.25) !important;
            color: #60a5fa !important;
        }
        [data-theme="dark"] .bg-primary.bg-opacity-10 i,
        body.dark-mode .bg-primary.bg-opacity-10 i {
            color: #60a5fa !important;
        }
        [data-theme="dark"] .bg-success.bg-opacity-10,
        body.dark-mode .bg-success.bg-opacity-10 {
            background-color: rgba(16, 185, 129, 0.25) !important;
            color: #34d399 !important;
        }
        [data-theme="dark"] .bg-secondary.bg-opacity-10,
        body.dark-mode .bg-secondary.bg-opacity-10 {
            background-color: #1e293b !important;
            color: #cbd5e1 !important;
        }

        /* Settings Receipt Printer Cards */
        [data-theme="dark"] .paper-width-card,
        body.dark-mode .paper-width-card {
            background: #1e293b !important;
            border-color: #334155 !important;
            color: #ffffff !important;
        }
        [data-theme="dark"] .paper-width-card.active,
        body.dark-mode .paper-width-card.active {
            background: rgba(37, 99, 235, 0.25) !important;
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 2px #3b82f6 !important;
        }
        [data-theme="dark"] .paper-width-card .paper-icon,
        body.dark-mode .paper-width-card .paper-icon {
            background: #0f172a !important;
            color: #38bdf8 !important;
            border: 1px solid #334155 !important;
        }
        [data-theme="dark"] .paper-width-card.active .paper-icon,
        body.dark-mode .paper-width-card.active .paper-icon {
            background: #2563eb !important;
            color: #ffffff !important;
            border-color: #3b82f6 !important;
        }
        [data-theme="dark"] #selectAllBar,
        body.dark-mode #selectAllBar {
            background-color: #1e293b !important;
            border-color: #334155 !important;
            color: #ffffff !important;
        }
        [data-theme="dark"] #toggleSelectBtn,
        body.dark-mode #toggleSelectBtn {
            background-color: #1e293b !important;
            border-color: #334155 !important;
            color: #cbd5e1 !important;
        }
        [data-theme="dark"] .offcanvas,
        [data-theme="dark"] .offcanvas-header,
        [data-theme="dark"] .offcanvas-body,
        [data-theme="dark"] .support-offcanvas,
        [data-theme="dark"] .support-card,
        body.dark-mode .offcanvas,
        body.dark-mode .offcanvas-header,
        body.dark-mode .offcanvas-body,
        body.dark-mode .support-offcanvas,
        body.dark-mode .support-card {
            background-color: #111827 !important;
            color: #f1f5f9 !important;
            border-color: #1f2937 !important;
        }
        [data-theme="dark"] .list-group-item,
        body.dark-mode .list-group-item {
            background-color: #111827 !important;
            border-color: #1f2937 !important;
            color: #f1f5f9 !important;
        }
        [data-theme="dark"] .swal2-popup,
        body.dark-mode .swal2-popup {
            background-color: #111827 !important;
            color: #ffffff !important;
            border: 1px solid #1f2937 !important;
        }
        [data-theme="dark"] .swal2-title,
        [data-theme="dark"] .swal2-html-container,
        body.dark-mode .swal2-title,
        body.dark-mode .swal2-html-container {
            color: #ffffff !important;
        }
        [data-theme="dark"] .select2-container--default .select2-selection--single,
        body.dark-mode .select2-container--default .select2-selection--single {
            background-color: #111827 !important;
            border-color: #374151 !important;
            color: #ffffff !important;
        }
        [data-theme="dark"] .select2-container--default .select2-selection--single .select2-selection__rendered,
        body.dark-mode .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #ffffff !important;
        }
        [data-theme="dark"] .select2-dropdown,
        body.dark-mode .select2-dropdown {
            background-color: #111827 !important;
            border-color: #374151 !important;
            color: #ffffff !important;
        }
        [data-theme="dark"] .select2-search--dropdown .select2-search__field,
        body.dark-mode .select2-search--dropdown .select2-search__field {
            background-color: #0b1120 !important;
            border-color: #374151 !important;
            color: #ffffff !important;
        }
        [data-theme="dark"] .select2-container--default .select2-results__option--highlighted[aria-selected],
        body.dark-mode .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #2563eb !important;
            color: #ffffff !important;
        }

        /* Pagination & Breadcrumbs */
        [data-theme="dark"] .pagination .page-item .page-link,
        body.dark-mode .pagination .page-item .page-link {
            background-color: #111827 !important;
            border-color: #1f2937 !important;
            color: #cbd5e1 !important;
        }
        [data-theme="dark"] .pagination .page-item.active .page-link,
        body.dark-mode .pagination .page-item.active .page-link {
            background-color: #2563eb !important;
            border-color: #2563eb !important;
            color: #ffffff !important;
        }
        [data-theme="dark"] .pagination .page-item.disabled .page-link,
        body.dark-mode .pagination .page-item.disabled .page-link {
            background-color: #0b1120 !important;
            border-color: #1f2937 !important;
            color: #475569 !important;
        }
        [data-theme="dark"] .breadcrumb-item,
        [data-theme="dark"] .breadcrumb-item a,
        body.dark-mode .breadcrumb-item,
        body.dark-mode .breadcrumb-item a {
            color: #94a3b8 !important;
        }
        [data-theme="dark"] .breadcrumb-item.active,
        body.dark-mode .breadcrumb-item.active {
            color: #f1f5f9 !important;
        }

        /* Supermarket & Retail POS Screen Full Dark Coverage */
        [data-theme="dark"] .spos-container,
        body.dark-mode .spos-container {
            background: #111827 !important;
            border-color: #1f2937 !important;
        }
        [data-theme="dark"] .spos-topbar,
        body.dark-mode .spos-topbar {
            background: #111827 !important;
            border-color: #1f2937 !important;
        }
        [data-theme="dark"] .spos-search-box,
        [data-theme="dark"] .spos-cat-btn,
        [data-theme="dark"] .spos-item-card,
        [data-theme="dark"] .spos-summary-box,
        [data-theme="dark"] .spos-cart-item,
        body.dark-mode .spos-search-box,
        body.dark-mode .spos-cat-btn,
        body.dark-mode .spos-item-card,
        body.dark-mode .spos-summary-box,
        body.dark-mode .spos-cart-item {
            background-color: #1e293b !important;
            border-color: #334155 !important;
            color: #ffffff !important;
        }
        [data-theme="dark"] .spos-cart-panel,
        body.dark-mode .spos-cart-panel {
            background: #0b1120 !important;
            border-color: #1f2937 !important;
        }

        /* Ref Codes & Shop Badges */
        [data-theme="dark"] .ref-code-badge {
            background: #1f2937 !important;
            border-color: #374151 !important;
            color: #93c5fd !important;
        }
        [data-theme="dark"] .shop-badge {
            background: #1f2937 !important;
            border-color: #374151 !important;
            color: #ffffff !important;
        }
        [data-theme="dark"] .stat-pill-blue { background: rgba(37,99,235,0.2) !important; color: #93c5fd !important; border-color: rgba(59,130,246,0.3) !important; }
        [data-theme="dark"] .stat-pill-emerald { background: rgba(5,150,105,0.2) !important; color: #6ee7b7 !important; border-color: rgba(16,185,129,0.3) !important; }
        [data-theme="dark"] .stat-pill-amber { background: rgba(217,119,6,0.2) !important; color: #fde68a !important; border-color: rgba(245,158,11,0.3) !important; }
        [data-theme="dark"] .stat-pill-rose { background: rgba(220,38,38,0.2) !important; color: #fca5a5 !important; border-color: rgba(239,68,68,0.3) !important; }

        [data-theme="dark"] .net-profit-hero.is-profit {
            background: linear-gradient(180deg, #111827 0%, rgba(6,95,70,0.25) 100%) !important;
            border-color: #1f2937 !important;
        }
        [data-theme="dark"] .net-profit-hero.is-loss {
            background: linear-gradient(180deg, #111827 0%, rgba(153,27,27,0.25) 100%) !important;
            border-color: #1f2937 !important;
        }
        [data-theme="dark"] .theme-toggle-btn {
            background: #1e293b !important;
            border-color: #334155 !important;
            color: #facc15 !important;
        }
        [data-theme="dark"] .exec-action-btn {
            background: #111827 !important;
            border-color: #374151 !important;
            color: #ffffff !important;
        }
        [data-theme="dark"] .pulse-marquee-wrapper {
            background: rgba(15, 23, 42, 0.4) !important;
            border-color: #1f2937 !important;
        }

        /* Full Icon Visibility Engine */
        [data-theme="dark"] i,
        body.dark-mode i,
        [data-theme="dark"] .bi,
        body.dark-mode .bi {
            opacity: 1 !important;
        }
        [data-theme="dark"] #sidebar .nav-icon i,
        body.dark-mode #sidebar .nav-icon i {
            opacity: 1 !important;
            color: inherit !important;
        }

        /* High Contrast Dark Mode Badges & Buttons */
        [data-theme="dark"] .dash-card-badge,
        body.dark-mode .dash-card-badge {
            background-color: #1e293b !important;
            border: 1px solid #334155 !important;
            color: #ffffff !important;
        }
        [data-theme="dark"] .dash-card-link-btn,
        body.dark-mode .dash-card-link-btn {
            background-color: #1e293b !important;
            border-color: #334155 !important;
            color: #ffffff !important;
        }
        [data-theme="dark"] .dash-card-link-btn:hover,
        body.dark-mode .dash-card-link-btn:hover {
            background-color: #334155 !important;
            color: #ffffff !important;
        }

        /* ApexCharts High Contrast Dark Mode Engine */
        [data-theme="dark"] .apexcharts-canvas text,
        body.dark-mode .apexcharts-canvas text,
        [data-theme="dark"] .apexcharts-canvas text.apexcharts-text,
        body.dark-mode .apexcharts-canvas text.apexcharts-text {
            fill: #cbd5e1 !important;
        }
        [data-theme="dark"] .apexcharts-canvas text.apexcharts-datalabel-value,
        body.dark-mode .apexcharts-canvas text.apexcharts-datalabel-value {
            fill: #ffffff !important;
        }
        [data-theme="dark"] .apexcharts-canvas text.apexcharts-datalabel-label,
        body.dark-mode .apexcharts-canvas text.apexcharts-datalabel-label {
            fill: #cbd5e1 !important;
        }
        [data-theme="dark"] .apexcharts-legend-text,
        body.dark-mode .apexcharts-legend-text {
            color: #ffffff !important;
        }

        /* Pale Gray Sidebar Boxes ("gray iliyopauka") */
        .sidebar-logo-box {
            width: 42px !important;
            height: 42px !important;
            overflow: hidden !important;
            display: flex !important;
            justify-content: center !important;
            align-items: center !important;
            border-radius: 11px !important;
            background: #e2e8f0 !important;
            border: 1.5px solid #cbd5e1 !important;
            flex-shrink: 0 !important;
            box-shadow: 0 1px 4px rgba(15, 23, 42, 0.06) !important;
            transition: background 0.2s ease, border-color 0.2s ease !important;
        }
        .sidebar-user-card {
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
            gap: 8px !important;
            background: #e2e8f0 !important;
            border: 1.5px solid #cbd5e1 !important;
            border-radius: 12px !important;
            padding: 8px 10px !important;
            transition: background 0.2s ease, border-color 0.2s ease !important;
        }
        .sidebar-brand-title {
            font-size: 1.5rem !important;
            font-weight: 800 !important;
            color: #1e293b !important;
            letter-spacing: -0.5px !important;
            font-family: 'Poppins', sans-serif !important;
            transition: color 0.2s ease !important;
        }
        .sidebar-user-name {
            font-size: 0.88rem !important;
            font-weight: 700 !important;
            line-height: 1.2 !important;
            color: #0f172a !important;
            transition: color 0.2s ease !important;
        }
        .sidebar-user-role {
            font-size: 0.74rem !important;
            font-weight: 600 !important;
            color: #475569 !important;
            transition: color 0.2s ease !important;
        }

        /* Dark Mode: Pale Gray Boxes and Pure White Z-POS Brand Text */
        [data-theme="dark"] .sidebar-logo-box,
        body.dark-mode .sidebar-logo-box {
            background: #334155 !important;
            border-color: #475569 !important;
        }
        [data-theme="dark"] .sidebar-brand-title,
        body.dark-mode .sidebar-brand-title {
            color: #ffffff !important;
        }
        [data-theme="dark"] .sidebar-user-card,
        body.dark-mode .sidebar-user-card {
            background: #334155 !important;
            border-color: #475569 !important;
        }
        [data-theme="dark"] .sidebar-user-name,
        body.dark-mode .sidebar-user-name {
            color: #ffffff !important;
        }
        [data-theme="dark"] .sidebar-user-role,
        body.dark-mode .sidebar-user-role {
            color: #ffffff !important;
        }
        [data-theme="dark"] .sidebar-user-card .btn-light,
        body.dark-mode .sidebar-user-card .btn-light {
            background-color: #1e293b !important;
            border-color: #475569 !important;
            color: #ffffff !important;
        }
        [data-theme="dark"] .sidebar-user-card .btn-light i,
        body.dark-mode .sidebar-user-card .btn-light i {
            color: #ffffff !important;
        }
        [data-theme="dark"] .sidebar-user-card .btn-dark,
        body.dark-mode .sidebar-user-card .btn-dark {
            background-color: #1e293b !important;
            border-color: #475569 !important;
            color: #38bdf8 !important;
        }

        .sidebar-user-avatar {
            width: 34px !important;
            height: 34px !important;
            border-radius: 9px !important;
            background: #0f172a !important;
            color: #facc15 !important;
            font-weight: 700 !important;
            font-size: 14px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            flex-shrink: 0 !important;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1) !important;
        }
        .sidebar-bottom .fw-bold {
            color: #0f172a !important;
            font-weight: 700 !important;
        }
        .sidebar-bottom .text-muted {
            color: #475569 !important;
            font-weight: 600 !important;
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
        /* ==========================================================================
           MODERN RESPONSIVE SWEETALERT & TOASTS (TOP-RIGHT ONLY)
           ========================================================================== */
        .swal2-container.swal2-top-end,
        .swal2-container.swal2-top-right {
            padding: 14px 16px !important;
            z-index: 100000 !important;
        }
        .swal2-popup.swal2-toast {
            padding: 10px 16px !important;
            width: auto !important;
            max-width: 380px !important;
            min-height: 44px !important;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.12), 0 8px 10px -6px rgba(0, 0, 0, 0.08) !important;
            border-radius: 12px !important;
            margin-top: 6px !important;
            display: flex !important;
            align-items: center !important;
            gap: 10px !important;
            border: 1px solid rgba(226, 232, 240, 0.8) !important;
            background: #ffffff !important;
            color: #0f172a !important;
        }
        .swal2-popup.swal2-toast .swal2-icon {
            transform: scale(0.65) !important;
            margin: 0 !important;
            flex-shrink: 0 !important;
        }
        .swal2-popup.swal2-toast .swal2-title {
            margin: 0 !important;
            font-size: 0.86rem !important;
            font-weight: 600 !important;
            color: #0f172a !important;
            line-height: 1.35 !important;
            padding: 0 !important;
            text-align: left !important;
            word-break: break-word !important;
        }
        .swal2-popup.swal2-toast .swal2-timer-progress-bar {
            height: 3px !important;
            border-bottom-left-radius: 12px !important;
            border-bottom-right-radius: 12px !important;
        }

        /* Modal Dialogs (Compact, Beautiful, Responsive) */
        .swal2-popup:not(.swal2-toast) {
            max-width: 380px !important;
            width: 90vw !important;
            padding: 1.5rem 1.25rem !important;
            border-radius: 16px !important;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2), 0 10px 10px -5px rgba(0, 0, 0, 0.1) !important;
        }
        .swal2-popup:not(.swal2-toast) .swal2-icon {
            transform: scale(0.75) !important;
            margin: 0.5rem auto 1rem !important;
        }
        .swal2-popup:not(.swal2-toast) .swal2-title {
            font-size: 1.1rem !important;
            font-weight: 700 !important;
            padding: 0 0 0.5rem !important;
        }
        .swal2-popup:not(.swal2-toast) .swal2-html-container {
            font-size: 0.875rem !important;
            margin: 0.25rem 0 1rem !important;
        }
        .swal2-popup:not(.swal2-toast) .swal2-actions {
            margin: 1rem 0 0 !important;
            gap: 8px !important;
        }
        .swal2-popup:not(.swal2-toast) .swal2-actions button {
            border-radius: 8px !important;
            font-size: 0.85rem !important;
            font-weight: 600 !important;
            padding: 8px 18px !important;
        }

        /* Sidebar Overlay Guarantee */
        .sidebar-overlay {
            display: none !important;
            pointer-events: none !important;
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1040;
            backdrop-filter: blur(2px);
            -webkit-backdrop-filter: blur(2px);
        }
        .sidebar-overlay.active {
            display: block !important;
            pointer-events: auto !important;
        }

        /* SweetAlert container must NEVER block clicks when showing toasts */
        .swal2-container.swal2-top-end,
        .swal2-container.swal2-top-right,
        .swal2-container.swal2-bottom-end,
        .swal2-container.swal2-bottom-right {
            pointer-events: none !important;
        }
        .swal2-popup.swal2-toast {
            pointer-events: auto !important;
        }

        /* Mobile adjustments */
        @media (max-width: 576px) {
            .swal2-container.swal2-top-end,
            .swal2-container.swal2-top-right {
                padding: 10px 12px !important;
                right: 0 !important;
                left: 0 !important;
                pointer-events: none !important;
            }
            .swal2-popup.swal2-toast {
                max-width: calc(100vw - 24px) !important;
                width: 100% !important;
                pointer-events: auto !important;
            }
        }

        /* Dark Mode Overrides for SweetAlert */
        [data-theme="dark"] .swal2-popup.swal2-toast,
        body.dark-mode .swal2-popup.swal2-toast {
            background: #1e293b !important;
            border-color: #334155 !important;
            color: #f8fafc !important;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.5) !important;
        }
        [data-theme="dark"] .swal2-popup.swal2-toast .swal2-title,
        body.dark-mode .swal2-popup.swal2-toast .swal2-title {
            color: #f8fafc !important;
        }
        [data-theme="dark"] .swal2-popup:not(.swal2-toast),
        body.dark-mode .swal2-popup:not(.swal2-toast) {
            background: #1e293b !important;
            border: 1px solid #334155 !important;
            color: #f8fafc !important;
        }
        [data-theme="dark"] .swal2-popup:not(.swal2-toast) .swal2-title,
        body.dark-mode .swal2-popup:not(.swal2-toast) .swal2-title {
            color: #ffffff !important;
        }
        [data-theme="dark"] .swal2-popup:not(.swal2-toast) .swal2-html-container,
        body.dark-mode .swal2-popup:not(.swal2-toast) .swal2-html-container {
            color: #cbd5e1 !important;
        }

        /* =============================================
           FLATPICKR DATEPICKER (DD/MM/YYYY) STYLES
           ============================================= */
        .flatpickr-calendar {
            font-family: 'Poppins', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
            border-radius: 12px !important;
            box-shadow: 0 14px 30px rgba(0, 0, 0, 0.12) !important;
            border: 1px solid #e2e8f0 !important;
            padding: 4px !important;
            z-index: 999999 !important;
        }
        .flatpickr-calendar.arrowTop:before,
        .flatpickr-calendar.arrowTop:after {
            border-bottom-color: #ffffff !important;
        }
        .flatpickr-months {
            border-radius: 10px 10px 0 0 !important;
            padding: 4px 0 !important;
        }
        .flatpickr-current-month {
            font-size: 0.95rem !important;
            font-weight: 600 !important;
            padding-top: 4px !important;
        }
        .flatpickr-current-month .flatpickr-monthDropdown-months {
            font-weight: 700 !important;
        }
        span.flatpickr-weekday {
            font-weight: 600 !important;
            color: #64748b !important;
            font-size: 0.8rem !important;
        }
        .flatpickr-day {
            border-radius: 8px !important;
            font-weight: 500 !important;
            height: 34px !important;
            line-height: 34px !important;
        }
        .flatpickr-day.today {
            border-color: #2563eb !important;
            background: rgba(37, 99, 235, 0.08) !important;
            color: #2563eb !important;
        }
        .flatpickr-day.selected,
        .flatpickr-day.startRange,
        .flatpickr-day.endRange {
            background: #2563eb !important;
            border-color: #2563eb !important;
            color: #ffffff !important;
            font-weight: 700 !important;
        }
        .flatpickr-day:hover {
            background: #f1f5f9 !important;
        }
        .flatpickr-alt-date {
            cursor: text !important;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%2364748b' class='bi bi-calendar3' viewBox='0 0 16 16'%3E%3Cpath d='M14 0H2a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zM1 3.857C1 3.384 1.448 3 2 3h12c.552 0 1 .384 1 .857v10.286c0 .473-.448.857-1 .857H2c-.552 0-1-.384-1-.857V3.857z'/%3E%3Cpath d='M6.5 7a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm-9 3a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm-9 3a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2z'/%3E%3C/svg%3E") !important;
            background-repeat: no-repeat !important;
            background-position: right 12px center !important;
            background-size: 15px 15px !important;
            padding-right: 36px !important;
        }

        /* Flatpickr Dark Mode */
        [data-theme="dark"] .flatpickr-calendar,
        .dark-mode .flatpickr-calendar {
            background: #1e293b !important;
            border-color: #334155 !important;
            box-shadow: 0 18px 38px rgba(0, 0, 0, 0.45) !important;
            color: #f8fafc !important;
        }
        [data-theme="dark"] .flatpickr-calendar.arrowTop:before,
        .dark-mode .flatpickr-calendar.arrowTop:before,
        [data-theme="dark"] .flatpickr-calendar.arrowTop:after,
        .dark-mode .flatpickr-calendar.arrowTop:after {
            border-bottom-color: #1e293b !important;
        }
        [data-theme="dark"] .flatpickr-months,
        .dark-mode .flatpickr-months {
            background: #0f172a !important;
            border-radius: 8px 8px 0 0 !important;
        }
        [data-theme="dark"] .flatpickr-month,
        .dark-mode .flatpickr-month,
        [data-theme="dark"] .flatpickr-current-month,
        .dark-mode .flatpickr-current-month {
            color: #f8fafc !important;
            fill: #f8fafc !important;
        }
        [data-theme="dark"] .flatpickr-current-month .flatpickr-monthDropdown-months,
        .dark-mode .flatpickr-current-month .flatpickr-monthDropdown-months {
            background: #0f172a !important;
            color: #f8fafc !important;
        }
        [data-theme="dark"] .flatpickr-current-month input.cur-year,
        .dark-mode .flatpickr-current-month input.cur-year {
            color: #f8fafc !important;
        }
        [data-theme="dark"] .flatpickr-current-month .numInputWrapper span.arrowUp:after,
        .dark-mode .flatpickr-current-month .numInputWrapper span.arrowUp:after {
            border-bottom-color: #f8fafc !important;
        }
        [data-theme="dark"] .flatpickr-current-month .numInputWrapper span.arrowDown:after,
        .dark-mode .flatpickr-current-month .numInputWrapper span.arrowDown:after {
            border-top-color: #f8fafc !important;
        }
        [data-theme="dark"] span.flatpickr-weekday,
        .dark-mode span.flatpickr-weekday {
            background: #1e293b !important;
            color: #94a3b8 !important;
        }
        [data-theme="dark"] .flatpickr-day,
        .dark-mode .flatpickr-day {
            color: #e2e8f0 !important;
        }
        [data-theme="dark"] .flatpickr-day.prevMonthDay,
        .dark-mode .flatpickr-day.prevMonthDay,
        [data-theme="dark"] .flatpickr-day.nextMonthDay,
        .dark-mode .flatpickr-day.nextMonthDay {
            color: #64748b !important;
        }
        [data-theme="dark"] .flatpickr-day:hover,
        .dark-mode .flatpickr-day:hover {
            background: #334155 !important;
            color: #ffffff !important;
        }
        [data-theme="dark"] .flatpickr-day.today,
        .dark-mode .flatpickr-day.today {
            border-color: #38bdf8 !important;
            background: rgba(56, 189, 248, 0.15) !important;
            color: #38bdf8 !important;
        }
        [data-theme="dark"] .flatpickr-day.selected,
        .dark-mode .flatpickr-day.selected {
            background: #2563eb !important;
            border-color: #2563eb !important;
            color: #ffffff !important;
        }
        [data-theme="dark"] .flatpickr-months .flatpickr-prev-month,
        .dark-mode .flatpickr-months .flatpickr-prev-month,
        [data-theme="dark"] .flatpickr-months .flatpickr-next-month,
        .dark-mode .flatpickr-months .flatpickr-next-month {
            fill: #f8fafc !important;
            color: #f8fafc !important;
        }
        [data-theme="dark"] .flatpickr-months .flatpickr-prev-month:hover svg,
        .dark-mode .flatpickr-months .flatpickr-prev-month:hover svg,
        [data-theme="dark"] .flatpickr-months .flatpickr-next-month:hover svg,
        .dark-mode .flatpickr-months .flatpickr-next-month:hover svg {
            fill: #38bdf8 !important;
        }
        [data-theme="dark"] .flatpickr-alt-date,
        .dark-mode .flatpickr-alt-date {
            background-color: #1e293b !important;
            color: #f8fafc !important;
            border-color: #334155 !important;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%2394a3b8' class='bi bi-calendar3' viewBox='0 0 16 16'%3E%3Cpath d='M14 0H2a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zM1 3.857C1 3.384 1.448 3 2 3h12c.552 0 1 .384 1 .857v10.286c0 .473-.448.857-1 .857H2c-.552 0-1-.384-1-.857V3.857z'/%3E%3Cpath d='M6.5 7a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm-9 3a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm-9 3a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2z'/%3E%3C/svg%3E") !important;
        }
    </style>
</head>
<body class="admin-body">
    <script>
        (function() {
            const shopId = '<?php echo e($activeShopId); ?>';
            const serverTheme = '<?php echo e($shopTheme); ?>';
            const localTheme = shopId ? localStorage.getItem('zpos_theme_shop_' + shopId) : null;
            const activeTheme = localTheme || serverTheme || 'light';
            if (activeTheme === 'dark') {
                document.body.classList.add('dark-mode');
            } else {
                document.body.classList.remove('dark-mode');
            }
        })();
    </script>
    <div class="sidebar-overlay" id="sidebarOverlay" style="display: none; pointer-events: none;"></div>
    <div class="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar" class="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-brand-box justify-content-center">
                    <a href="<?php echo e(url('/home')); ?>" class="d-flex align-items-center justify-content-center text-decoration-none py-1 px-2">
                        <div class="sidebar-logo-box">
                            <img src="<?php echo e(asset('images/logo_pos.png')); ?>" alt="Z-pos Icon" style="width: 100%; height: 100%; object-fit: cover; transform: scale(2.2);">
                        </div>
                        <div class="ms-2 d-flex flex-column justify-content-center">
                            <span class="sidebar-brand-title lh-1"><?php echo e(__('Z-pos')); ?></span>
                        </div>
                    </a>
                </div>
            </div>

            <div class="sidebar-scroll-area">
            <?php if(!Auth::check() || !Auth::user()->hasRole('Super Admin') || Auth::user()->shop_id): ?>
            <?php $isPetroleum = Auth::check() && Auth::user()->shop && Auth::user()->shop->business_type === 'Petroleum Station / Fuel'; ?>
            <ul class="list-unstyled components" id="sidebarMenu">
                
                

                <li class="<?php echo e(request()->is('/') || request()->is('home') ? 'active' : ''); ?>">
                    <a href="<?php echo e(url('/home')); ?>" class="sidebar-nav-link">
                        <span class="nav-icon nav-icon-dashboard" style="color: #4f46e5;"><i class="bi bi-grid-fill"></i></span>
                        <span class="nav-label"><?php echo e(__('Dashboard')); ?></span>
                    </a>
                </li>

                <?php if(!$isPetroleum): ?>
                

                <li>
                    <div class="custom-dropdown-parent <?php echo e(request()->is('sales*') || request()->is('returns*') || request()->is('invoices*') ? 'active-dropdown' : ''); ?>">
                        <a href="#salesSubmenu" data-bs-toggle="collapse" aria-expanded="false" class="sidebar-nav-link dropdown-toggle">
                            <span class="nav-icon nav-icon-sales" style="color: #10b981;"><i class="bi bi-cart-check-fill"></i></span>
                            <span class="nav-label flex-grow-1"><?php echo e(__('Sales')); ?></span>
                            <i class="bi bi-chevron-down menu-chevron"></i>
                        </a>
                        <ul class="collapse list-unstyled custom-submenu" id="salesSubmenu" data-bs-parent="#sidebarMenu">
                            <li><a href="<?php echo e(route('sales.create')); ?>" class="<?php echo e(request()->routeIs('sales.create') ? 'active' : ''); ?>"><?php echo e(__('New Sale')); ?></a></li>
                            <li><a href="<?php echo e(route('sales.index')); ?>" class="<?php echo e(request()->routeIs('sales.index') ? 'active' : ''); ?>"><?php echo e(__('Sales History')); ?></a></li>
                            <?php if(Auth::user()->shop && !in_array(Auth::user()->shop->business_type, ['Restaurant / Food', 'Services / Consulting', 'Clothing / Boutique', 'Supermarket / Grocery'])): ?>
                            <li><a href="<?php echo e(route('returns.index')); ?>" class="<?php echo e(request()->routeIs('returns.index') ? 'active' : ''); ?>"><?php echo e(__('Return Invoices')); ?></a></li>
                            <li>
                                <a href="<?php echo e(route('returns.defective')); ?>" class="<?php echo e(request()->is('returns/defective*') ? 'active text-danger fw-semibold' : ''); ?>">
                                    <?php echo e(__('Defective Items')); ?>

                                </a>
                            </li>
                            <?php endif; ?>
                            <?php if(Auth::user()->shop && Auth::user()->shop->business_type === 'Supermarket / Grocery' && Auth::user()->hasRole('Administrator')): ?>
                            <li>
                                <a href="<?php echo e(route('defective-stock.index')); ?>" class="<?php echo e(request()->is('defective-stock*') ? 'active text-danger fw-semibold' : ''); ?>">
                                    <?php echo e(__('Defective Items')); ?>

                                </a>
                            </li>
                            <?php endif; ?>
                            <li><a href="<?php echo e(route('invoices.index')); ?>" class="<?php echo e(request()->routeIs('invoices.*') ? 'active' : ''); ?>"><?php echo e(__('Invoices')); ?></a></li>
                        </ul>
                    </div>
                </li>
                <?php endif; ?>

                

                <?php if(Auth::user()->hasRole('Administrator')): ?>
                    <?php if(!$isPetroleum): ?>
                    <li>
                        <div class="custom-dropdown-parent <?php echo e(request()->is('products*') || request()->is('categories*') || request()->is('brands*') || request()->is('units*') || request()->is('customers*') ? 'active-dropdown' : ''); ?>">
                            <a href="#itemsSubmenu" data-bs-toggle="collapse" aria-expanded="false" class="sidebar-nav-link dropdown-toggle">
                                <span class="nav-icon nav-icon-items" style="color: #3b82f6;"><i class="bi bi-boxes"></i></span>
                                <span class="nav-label flex-grow-1"><?php echo e(__('Items & Catalog')); ?></span>
                                <i class="bi bi-chevron-down menu-chevron"></i>
                            </a>
                            <ul class="collapse list-unstyled custom-submenu" id="itemsSubmenu" data-bs-parent="#sidebarMenu">
                                <li><a href="<?php echo e(route('products.index')); ?>" class="<?php echo e(request()->routeIs('products.*') ? 'active' : ''); ?>"><?php echo e(__('Products')); ?></a></li>
                                <li><a href="<?php echo e(route('categories.index')); ?>" class="<?php echo e(request()->routeIs('categories.*') ? 'active' : ''); ?>"><?php echo e(__('Categories')); ?></a></li>
                                <li><a href="<?php echo e(route('brands.index')); ?>" class="<?php echo e(request()->routeIs('brands.*') ? 'active' : ''); ?>"><?php echo e(__('Brands')); ?></a></li>
                                <li><a href="<?php echo e(route('units.index')); ?>" class="<?php echo e(request()->routeIs('units.*') ? 'active' : ''); ?>"><?php echo e(__('Units')); ?></a></li>
                                <li><a href="<?php echo e(route('customers.index')); ?>" class="<?php echo e(request()->routeIs('customers.*') ? 'active' : ''); ?>"><?php echo e(__('Customers')); ?></a></li>
                            </ul>
                        </div>
                    </li>
                    <li>
                        <div class="custom-dropdown-parent <?php echo e(request()->is('purchases*') || request()->is('suppliers*') ? 'active-dropdown' : ''); ?>">
                            <a href="#purchasesSubmenu" data-bs-toggle="collapse" aria-expanded="false" class="sidebar-nav-link dropdown-toggle">
                                <span class="nav-icon nav-icon-purchases" style="color: #8b5cf6;"><i class="bi bi-bag-plus-fill"></i></span>
                                <span class="nav-label flex-grow-1"><?php echo e(__('Purchases')); ?></span>
                                <i class="bi bi-chevron-down menu-chevron"></i>
                            </a>
                            <ul class="collapse list-unstyled custom-submenu" id="purchasesSubmenu" data-bs-parent="#sidebarMenu">
                                <li><a href="<?php echo e(route('purchases.create')); ?>" class="<?php echo e(request()->routeIs('purchases.create') ? 'active' : ''); ?>"><?php echo e(__('Add Purchase')); ?></a></li>
                                <li><a href="<?php echo e(route('purchases.index')); ?>" class="<?php echo e(request()->routeIs('purchases.index') ? 'active' : ''); ?>"><?php echo e(__('Purchase History')); ?></a></li>
                                <li><a href="<?php echo e(route('suppliers.index')); ?>" class="<?php echo e(request()->routeIs('suppliers.*') ? 'active' : ''); ?>"><?php echo e(__('Suppliers')); ?></a></li>
                            </ul>
                        </div>
                    </li>
                    <?php endif; ?>
                <?php else: ?>
                    <?php if(!$isPetroleum): ?>
                    <li class="<?php echo e(request()->is('products*') ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('products.index')); ?>" class="sidebar-nav-link">
                            <span class="nav-icon nav-icon-items" style="color: #3b82f6;"><i class="bi bi-boxes"></i></span>
                            <span class="nav-label"><?php echo e(__('Items')); ?></span>
                        </a>
                    </li>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if(Auth::user()->hasRole('Administrator') && Auth::user()->shop && !in_array(Auth::user()->shop->business_type, ['Restaurant / Food', 'Petroleum Station / Fuel'])): ?>
                <li>
                    <div class="custom-dropdown-parent <?php echo e(request()->is('warehouses*') || request()->is('stock-transfer*') || request()->is('stock-movements*') ? 'active-dropdown' : ''); ?>">
                        <a href="#warehouseSubmenu" data-bs-toggle="collapse" aria-expanded="false" class="sidebar-nav-link dropdown-toggle">
                            <span class="nav-icon nav-icon-warehouse" style="color: #f59e0b;"><i class="bi bi-building"></i></span>
                            <span class="nav-label flex-grow-1"><?php echo e(__('Warehouse')); ?></span>
                            <i class="bi bi-chevron-down menu-chevron"></i>
                        </a>
                        <ul class="collapse list-unstyled custom-submenu" id="warehouseSubmenu" data-bs-parent="#sidebarMenu">
                            <li><a href="<?php echo e(route('warehouses.index')); ?>" class="<?php echo e(request()->routeIs('warehouses.*') ? 'active' : ''); ?>"><?php echo e(__('Warehouses & Stock')); ?></a></li>
                            <li><a href="<?php echo e(route('stock.transfer.create')); ?>" class="<?php echo e(request()->routeIs('stock.transfer.create') ? 'active' : ''); ?>"><?php echo e(__('Transfer Stock')); ?></a></li>
                            <li><a href="<?php echo e(route('stock.movements.index')); ?>" class="<?php echo e(request()->routeIs('stock.movements.index') ? 'active' : ''); ?>"><?php echo e(__('Stock Movements')); ?></a></li>
                        </ul>
                    </div>
                </li>
                <?php endif; ?>

                <?php if(Auth::user()->shop && in_array(Auth::user()->shop->business_type, ['Electronics / IT', 'Hardware / Construction', 'Retail / General'])): ?>
                <li class="<?php echo e(request()->is('warranties*') ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('warranties.index')); ?>" class="sidebar-nav-link">
                        <span class="nav-icon nav-icon-warranties" style="color: #0d9488;"><i class="bi bi-shield-check"></i></span>
                        <span class="nav-label"><?php echo e(__('Warranties')); ?></span>
                    </a>
                </li>
                <?php endif; ?>

                
                <?php if($isPetroleum): ?>

                <li class="<?php echo e(request()->is('petroleum/sales*') ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('petroleum.sales.index')); ?>" class="sidebar-nav-link">
                        <span class="nav-icon nav-icon-petroleum" style="color: #ea580c;"><i class="bi bi-fuel-pump"></i></span>
                        <span class="nav-label"><?php echo e(__('Fuel Sales')); ?></span>
                    </a>
                </li>
                <li class="<?php echo e(request()->is('petroleum/day*') ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('petroleum.day.index')); ?>" class="sidebar-nav-link">
                        <span class="nav-icon nav-icon-station-day" style="color: #0284c7;"><i class="bi bi-calendar2-check"></i></span>
                        <span class="nav-label"><?php echo e(__('Station Day')); ?></span>
                    </a>
                </li>
                <li class="<?php echo e(request()->is('invoices*') ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('invoices.index')); ?>" class="sidebar-nav-link">
                        <span class="nav-icon nav-icon-invoices" style="color: #4f46e5;"><i class="bi bi-receipt"></i></span>
                        <span class="nav-label"><?php echo e(__('Invoices')); ?></span>
                    </a>
                </li>
                <li class="<?php echo e(request()->is('customers*') ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('customers.index')); ?>" class="sidebar-nav-link">
                        <span class="nav-icon nav-icon-customers" style="color: #3b82f6;"><i class="bi bi-people-fill"></i></span>
                        <span class="nav-label"><?php echo e(__('Customers')); ?></span>
                    </a>
                </li>
                <?php if(Auth::user()->hasRole('Administrator')): ?>
                <li>
                    <div class="custom-dropdown-parent <?php echo e(request()->is('petroleum/deliveries*') || request()->is('petroleum/tanks*') || request()->is('petroleum/pumps*') || request()->is('petroleum/nozzles*') ? 'active-dropdown' : ''); ?>">
                        <a href="#petroleumSetupSubmenu" data-bs-toggle="collapse" aria-expanded="false" class="sidebar-nav-link dropdown-toggle">
                            <span class="nav-icon nav-icon-station-setup" style="color: #64748b;"><i class="bi bi-gear-wide-connected"></i></span>
                            <span class="nav-label flex-grow-1"><?php echo e(__('Station Setup')); ?></span>
                            <i class="bi bi-chevron-down menu-chevron"></i>
                        </a>
                        <ul class="collapse list-unstyled custom-submenu" id="petroleumSetupSubmenu" data-bs-parent="#sidebarMenu">
                            <li><a href="<?php echo e(route('petroleum.deliveries.index')); ?>"><?php echo e(__('Deliveries')); ?></a></li>
                            <li><a href="<?php echo e(route('petroleum.tanks.index')); ?>"><?php echo e(__('Tanks')); ?></a></li>
                            <li><a href="<?php echo e(route('petroleum.pumps.index')); ?>"><?php echo e(__('Pumps')); ?></a></li>
                            <li><a href="<?php echo e(route('petroleum.nozzles.index')); ?>"><?php echo e(__('Nozzles')); ?></a></li>
                        </ul>
                    </div>
                </li>
                <?php endif; ?>
                <?php endif; ?>

                

                <li class="<?php echo e(request()->is('expenses*') ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('expenses.index')); ?>" class="sidebar-nav-link">
                        <span class="nav-icon nav-icon-expenses" style="color: #ef4444;"><i class="bi bi-graph-down-arrow"></i></span>
                        <span class="nav-label"><?php echo e(__('Expenses')); ?></span>
                    </a>
                </li>
                <?php if(Auth::user()->hasRole('Administrator')): ?>
                <li>
                    <div class="custom-dropdown-parent <?php echo e(request()->is('reports*') ? 'active-dropdown' : ''); ?>">
                        <a href="#reportsSubmenu" data-bs-toggle="collapse" aria-expanded="false" class="sidebar-nav-link dropdown-toggle">
                            <span class="nav-icon nav-icon-reports" style="color: #0ea5e9;"><i class="bi bi-pie-chart-fill"></i></span>
                            <span class="nav-label flex-grow-1"><?php echo e(__('Reports')); ?></span>
                            <i class="bi bi-chevron-down menu-chevron"></i>
                        </a>
                        <ul class="collapse list-unstyled custom-submenu" id="reportsSubmenu" data-bs-parent="#sidebarMenu">
                            <li><a href="<?php echo e(route('reports.index')); ?>" class="<?php echo e(request()->routeIs('reports.index') ? 'active' : ''); ?>"><?php echo e(__('Overview')); ?></a></li>
                            <?php if(!$isPetroleum): ?>
                            <li><a href="<?php echo e(route('reports.profit_loss')); ?>" class="<?php echo e(request()->routeIs('reports.profit_loss') ? 'active' : ''); ?>"><?php echo e(__('Profit and Loss')); ?></a></li>
                            <li><a href="<?php echo e(route('reports.sales')); ?>" class="<?php echo e(request()->routeIs('reports.sales') ? 'active' : ''); ?>"><?php echo e(__('Sales')); ?></a></li>
                            <li><a href="<?php echo e(route('reports.stock_movement')); ?>" class="<?php echo e(request()->routeIs('reports.stock_movement') ? 'active' : ''); ?>"><?php echo e(__('Stock Movement')); ?></a></li>
                            <li><a href="<?php echo e(route('reports.damaged_stock')); ?>" class="<?php echo e(request()->routeIs('reports.damaged_stock*') ? 'active' : ''); ?>"><?php echo e(__('Damaged Stock')); ?></a></li>
                            <?php endif; ?>
                            <li><a href="<?php echo e(route('reports.expenses')); ?>" class="<?php echo e(request()->routeIs('reports.expenses') ? 'active' : ''); ?>"><?php echo e(__('Expenses')); ?></a></li>
                        </ul>
                    </div>
                </li>
                <?php endif; ?>

                

                <?php if(Auth::user()->shop && in_array(Auth::user()->shop->package, ['professional', 'enterprise']) && Auth::user()->hasRole('Administrator')): ?>
                <li class="<?php echo e(request()->is('branches*') ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('branches.index')); ?>" class="sidebar-nav-link">
                        <span class="nav-icon nav-icon-branches" style="color: #8b5cf6;"><i class="bi bi-shop"></i></span>
                        <span class="nav-label"><?php echo e(__('Branches')); ?></span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if(Auth::user()->hasRole('Administrator')): ?>
                <li class="<?php echo e(request()->is('staff*') ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('staff.index')); ?>" class="sidebar-nav-link">
                        <span class="nav-icon nav-icon-staff" style="color: #06b6d4;"><i class="bi bi-people-fill"></i></span>
                        <span class="nav-label"><?php echo e(__('Staff & Users')); ?></span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if(Auth::user()->shop): ?>
                <li class="<?php echo e(request()->routeIs('shop.business-card') ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('shop.business-card')); ?>" class="sidebar-nav-link">
                        <span class="nav-icon nav-icon-card" style="color: #10b981;"><i class="bi bi-person-badge-fill"></i></span>
                        <span class="nav-label"><?php echo e(__('Business Card')); ?></span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if(Auth::user()->hasRole('Administrator')): ?>
                <li class="<?php echo e(request()->routeIs('shop.settings*') ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('shop.settings')); ?>" class="sidebar-nav-link">
                        <span class="nav-icon nav-icon-settings" style="color: #475569;"><i class="bi bi-gear-fill"></i></span>
                        <span class="nav-label"><?php echo e(__('Shop Settings')); ?></span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if(Auth::user()->shop): ?>
                <li class="<?php echo e(request()->routeIs('plans.index') ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('plans.index')); ?>" class="sidebar-nav-link">
                        <span class="nav-icon nav-icon-plans" style="color: #f59e0b;"><i class="bi bi-gem"></i></span>
                        <span class="nav-label"><?php echo e(__('Upgrade Plan')); ?></span>
                    </a>
                </li>
                <li class="<?php echo e(request()->routeIs('reviews.index') ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('reviews.index')); ?>" class="sidebar-nav-link">
                        <span class="nav-icon nav-icon-reviews" style="color: #eab308;"><i class="bi bi-star-fill"></i></span>
                        <span class="nav-label"><?php echo e(__('Rate Us')); ?></span>
                    </a>
                </li>
                <?php endif; ?>
            </ul>
            <?php endif; ?>

            <?php if(auth()->check() && auth()->user()->hasRole('Super Admin')): ?>
            <ul class="list-unstyled components mt-3" id="sidebarSuperAdminMenu">

                <li class="<?php echo e(request()->routeIs('superadmin.shops.*') ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('superadmin.shops.index')); ?>" class="sidebar-nav-link">
                        <span class="nav-icon nav-icon-superadmin-shops" style="color: #4f46e5;"><i class="bi bi-buildings-fill"></i></span>
                        <span class="nav-label"><?php echo e(__('Manage Shops')); ?></span>
                    </a>
                </li>
                <li class="<?php echo e(request()->routeIs('superadmin.payments.*') ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('superadmin.payments.index')); ?>" class="sidebar-nav-link">
                        <span class="nav-icon nav-icon-superadmin-payments" style="color: #10b981;"><i class="bi bi-credit-card-fill"></i></span>
                        <span class="nav-label flex-grow-1"><?php echo e(__('Manage Payments')); ?></span>
                        <?php
                            $pendingCount = \App\Models\Payment::where('status', 'pending')->count();
                        ?>
                        <?php if($pendingCount > 0): ?>
                            <span class="badge bg-danger rounded-pill"><?php echo e($pendingCount); ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <li class="<?php echo e(request()->routeIs('superadmin.cms.*') || request()->routeIs('superadmin.testimonials.*') ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('superadmin.cms.index')); ?>" class="sidebar-nav-link">
                        <span class="nav-icon nav-icon-superadmin-cms" style="color: #8b5cf6;"><i class="bi bi-pencil-square"></i></span>
                        <span class="nav-label"><?php echo e(__('Content (CMS)')); ?></span>
                    </a>
                </li>
            </ul>
            <?php endif; ?>
            </div>

            <div class="sidebar-bottom">
                <?php if(auth()->check() && auth()->user()->hasRole('Super Admin')): ?>
                <div class="maintenance-toggle d-flex align-items-center justify-content-between p-2 px-3 bg-white rounded border mb-2 mx-1 shadow-xs">
                    <div>
                        <div class="fw-bold text-dark" style="font-size: 0.8rem;"><?php echo e(__('Maintenance')); ?></div>
                        <div class="text-muted" style="font-size: 0.72rem;" id="maintenanceStatusText"><?php echo e(\Illuminate\Support\Facades\Cache::get('site_maintenance') ? 'Site Down' : 'Site Live'); ?></div>
                    </div>
                    <div class="form-check form-switch m-0">
                        <input class="form-check-input" type="checkbox" role="switch" id="maintenanceSwitch" <?php echo e(\Illuminate\Support\Facades\Cache::get('site_maintenance') ? 'checked' : ''); ?> onchange="toggleMaintenance()">
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if(auth()->check()): ?>
                <div class="sidebar-user-card">
                    <div class="d-flex align-items-center overflow-hidden gap-2">
                        <div class="sidebar-user-avatar" style="background: #0f172a; color: #facc15;">
                            <?php echo e(strtoupper(substr(auth()->user()->name, 0, 1))); ?>

                        </div>
                        <div class="overflow-hidden">
                            <div class="sidebar-user-name text-truncate"><?php echo e(auth()->user()->name); ?></div>
                            <div class="sidebar-user-role text-truncate"><?php echo e(auth()->user()->roles->first()?->name ?? 'User'); ?></div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-1">
                        <?php if(Auth::check() && Auth::user()->hasRole('Administrator')): ?>
                        <a href="<?php echo e(route('shop.settings')); ?>" class="btn btn-sm btn-light border rounded-circle d-flex align-items-center justify-content-center shadow-xs" title="<?php echo e(__('Shop Settings')); ?>" style="width: 32px; height: 32px; flex-shrink: 0;">
                            <i class="bi bi-gear-fill" style="font-size: 0.9rem;"></i>
                        </a>
                        <?php endif; ?>
                        <button class="btn btn-sm btn-dark text-white rounded-circle shadow-sm d-flex align-items-center justify-content-center" type="button" data-bs-toggle="offcanvas" data-bs-target="#supportOffcanvas" title="<?php echo e(__('Customer Support')); ?>" style="width: 32px; height: 32px; flex-shrink: 0;">
                            <i class="bi bi-headset" style="font-size: 0.9rem;"></i>
                        </button>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </nav>
        
        <script>
        function toggleMaintenance() {
            fetch('<?php echo e(route('maintenance.toggle')); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
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
            <nav class="navbar navbar-expand-lg top-navbar shadow-sm border-bottom" style="background-color: #3b82f6; padding: 12px 20px;">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn text-white" style="background: transparent; border: none; min-width: 44px; min-height: 44px; display: inline-flex; align-items: center; justify-content: center;">
                        <i class="bi bi-chevron-double-left fs-5"></i>
                    </button>

                    <div class="d-flex align-items-center ms-auto gap-2 gap-sm-3">
                        <!-- Branch Switcher -->
                        <?php if(Auth::user()->hasRole('Administrator') && Auth::user()->shop && in_array(Auth::user()->shop->package, ['professional', 'enterprise'])): ?>
                            <?php
                                $userBranches = \App\Models\Branch::where('shop_id', Auth::user()->shop_id)->get();
                            ?>
                            <?php if($userBranches->count() > 1): ?>
                            <div class="dropdown">
                                <button class="btn btn-light dropdown-toggle bg-white border d-flex align-items-center rounded-pill px-2 px-sm-3 shadow-sm" type="button" id="branchDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="font-weight: 600; font-size: 0.85rem; color: #334155;">
                                    <i class="bi bi-shop me-1 me-sm-2 text-primary"></i>
                                    <?php
                                        $activeBranchId = session('active_branch_id');
                                        $activeBranch = $activeBranchId ? $userBranches->where('id', $activeBranchId)->first() : null;
                                    ?>
                                    <span class="text-truncate d-inline-block" style="max-width: 90px;"><?php echo e($activeBranch ? $activeBranch->name : __('All Branches')); ?></span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2" aria-labelledby="branchDropdown">
                                    <li>
                                        <form action="<?php echo e(route('branch.switch')); ?>" method="POST">
                                            <?php echo csrf_field(); ?>
                                            <input type="hidden" name="branch_id" value="">
                                            <button type="submit" class="dropdown-item py-2 <?php echo e(!$activeBranchId ? 'active bg-primary text-white' : ''); ?>">
                                                <i class="bi bi-globe me-2"></i> <?php echo e(__('All Branches (Overall)')); ?>

                                            </button>
                                        </form>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <?php $__currentLoopData = $userBranches; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li>
                                            <form action="<?php echo e(route('branch.switch')); ?>" method="POST">
                                                <?php echo csrf_field(); ?>
                                                <input type="hidden" name="branch_id" value="<?php echo e($b->id); ?>">
                                                <button type="submit" class="dropdown-item py-2 <?php echo e($activeBranchId == $b->id ? 'active bg-primary text-white' : ''); ?>">
                                                    <i class="bi bi-geo-alt me-2"></i> <?php echo e($b->name); ?>

                                                </button>
                                            </form>
                                        </li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                            <?php endif; ?>
                        <?php elseif(Auth::user()->branch): ?>
                            <span class="btn btn-light bg-white border d-flex align-items-center rounded-pill px-2 px-sm-3 shadow-sm" style="font-weight: 600; font-size: 0.85rem; color: #334155; cursor: default;">
                                <i class="bi bi-shop me-1 me-sm-2 text-primary"></i>
                                <span class="text-truncate d-inline-block" style="max-width: 120px;"><?php echo e(Auth::user()->branch->name); ?></span>
                            </span>
                        <?php endif; ?>

                        <!-- Language Switcher -->
                        <div class="dropdown">
                            <a class="btn btn-light bg-white rounded-pill px-2 px-sm-3 py-1 shadow-sm d-flex align-items-center text-decoration-none dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="border: 1px solid #e2e8f0; font-weight: 600; font-size: 0.85rem; color: #334155; white-space: nowrap;">
                                <?php if(App::getLocale() == 'en'): ?>
                                    <img src="https://flagcdn.com/w20/gb.png" alt="UK" class="me-1" style="width: 18px; border-radius: 2px;"> <span class="d-none d-sm-inline"><?php echo e(__('ENG')); ?></span>
                                <?php else: ?>
                                    <img src="https://flagcdn.com/w20/tz.png" alt="Tanzania" class="me-1" style="width: 18px; border-radius: 2px;"> <span class="d-none d-sm-inline"><?php echo e(__('SW')); ?></span>
                                <?php endif; ?>
                            </a>
                            <ul class="dropdown-menu shadow-sm border-0 mt-2" style="min-width: auto; padding: 0.5rem;">
                                <li><a class="dropdown-item d-flex align-items-center py-2" href="<?php echo e(route('lang.switch', 'sw')); ?>"><img src="https://flagcdn.com/w20/tz.png" class="me-2" style="width: 20px; border-radius: 2px;"> <?php echo e(__('Swahili')); ?></a></li>
                                <li><a class="dropdown-item d-flex align-items-center py-2" href="<?php echo e(route('lang.switch', 'en')); ?>"><img src="https://flagcdn.com/w20/gb.png" class="me-2" style="width: 20px; border-radius: 2px;"> <?php echo e(__('English')); ?></a></li>
                            </ul>
                        </div>

                        <!-- Theme Toggle Button (Dark / Light Mode) -->
                        <button type="button" id="themeToggleBtn" class="btn btn-light bg-white rounded-pill px-2 px-sm-3 py-1 shadow-sm d-flex align-items-center justify-content-center theme-toggle-btn" style="border: 1px solid #e2e8f0; color: #334155; font-weight: 600; font-size: 0.85rem; height: 35px; transition: all 0.2s ease;" title="<?php echo e(__('Toggle Dark / Light Mode')); ?>">
                            <i class="bi bi-moon-stars-fill theme-icon-moon text-primary me-1" style="font-size: 0.95rem;"></i>
                            <i class="bi bi-sun-fill theme-icon-sun d-none text-warning me-1" style="font-size: 1rem;"></i>
                            <span class="theme-mode-text d-none d-md-inline" style="font-size: 0.78rem;"><?php echo e(__('Mode')); ?></span>
                        </button>

                        <!-- Notifications Bell -->
                        <?php if(Auth::user()->hasRole('Administrator')): ?>
                        <a class="text-white position-relative d-flex align-items-center justify-content-center" href="<?php echo e(route('notifications.index')); ?>" style="font-size: 1.25rem; width: 36px; height: 36px; border-radius: 50%; background: rgba(255,255,255,0.15); transition: background 0.2s ease; text-decoration: none;" onmouseover="this.style.background='rgba(255,255,255,0.25)'" onmouseout="this.style.background='rgba(255,255,255,0.15)'">
                            <i class="bi bi-bell-fill" style="font-size: 1.1rem;"></i>
                            <?php $unreadCount = Auth::user()->unreadNotifications->count(); ?>
                            <?php if($unreadCount > 0): ?>
                                <span class="position-absolute top-0 end-0 badge rounded-pill bg-danger" style="font-size: 0.55rem; min-width: 16px; height: 16px; padding: 0 4px; display: flex; align-items: center; justify-content: center;">
                                    <?php echo e($unreadCount > 99 ? '99+' : $unreadCount); ?>

                                </span>
                            <?php endif; ?>
                        </a>
                        <?php else: ?>
                        
                        <a class="text-white position-relative d-flex align-items-center justify-content-center" href="<?php echo e(route('notifications.index')); ?>" style="font-size: 1.25rem; width: 36px; height: 36px; border-radius: 50%; background: rgba(255,255,255,0.15); transition: background 0.2s ease; text-decoration: none;" onmouseover="this.style.background='rgba(255,255,255,0.25)'" onmouseout="this.style.background='rgba(255,255,255,0.15)'">
                            <i class="bi bi-bell-fill" style="font-size: 1.1rem;"></i>
                            <?php $unreadCount = Auth::user()->unreadNotifications->count(); ?>
                            <?php if($unreadCount > 0): ?>
                                <span class="position-absolute top-0 end-0 badge rounded-pill bg-danger" style="font-size: 0.55rem; min-width: 16px; height: 16px; padding: 0 4px; display: flex; align-items: center; justify-content: center;">
                                    <?php echo e($unreadCount > 99 ? '99+' : $unreadCount); ?>

                                </span>
                            <?php endif; ?>
                        </a>
                        <?php endif; ?>

                        <!-- User Avatar Dropdown -->
                        <div class="dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center text-white p-0" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="position-relative">
                                    <img src="https://ui-avatars.com/api/?name=<?php echo e(urlencode(Auth::user()->name)); ?>&background=ffffff&color=3b82f6" alt="User" class="rounded-circle shadow-sm" width="36" height="36">
                                    <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-white rounded-circle" style="width:10px;height:10px;"></span>
                                </div>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                                <?php if(Auth::user()->shop && Auth::user()->hasRole('Administrator')): ?>
                                <li><a class="dropdown-item py-2" href="<?php echo e(route('shop.settings')); ?>"><i class="bi bi-shop me-2"></i> <?php echo e(__('Shop Settings')); ?></a></li>
                                <li><hr class="dropdown-divider"></li>
                                <?php endif; ?>
                                <li>
                                    <a class="dropdown-item py-2 text-danger" href="<?php echo e(route('logout')); ?>"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="bi bi-box-arrow-right me-2"></i> <?php echo e(__('Logout')); ?>

                                    </a>
                                    <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" class="d-none">
                                        <?php echo csrf_field(); ?>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Main Content Area -->
            <div class="container-fluid py-4 px-3 px-md-4">
                <?php if(!request()->is('/') && !request()->is('home*') && !request()->routeIs('home') && !request()->is('dashboard*') && !request()->routeIs('dashboard') && !View::hasSection('hide_back_btn')): ?>
                    <div class="mb-3">
                        <a href="javascript:history.back()" class="btn btn-light border bg-white shadow-sm rounded-pill px-3 fw-bold" style="color: #475569; transition: all 0.2s ease;">
                            <i class="bi bi-arrow-left me-1"></i> <?php echo e(__('Back')); ?>

                        </a>
                    </div>
                <?php endif; ?>
                <?php echo $__env->yieldContent('content'); ?>
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
                    <h5 class="offcanvas-title fw-bold mb-0" id="supportOffcanvasLabel"><?php echo e(__('Customer Support')); ?></h5>
                    <small class="text-white-50"><?php echo e(__('Need Help with Z-pos?')); ?></small>
                </div>
            </div>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="<?php echo e(__('Close')); ?>"></button>
        </div>
        <div class="offcanvas-body p-4 bg-light">
            <p class="text-muted mb-4" style="font-size: 0.95rem;"><?php echo e(__('Our support team is always here to assist you. Choose a channel below to reach out.')); ?></p>
            
            <a href="https://wa.me/255683628142" target="_blank" class="text-decoration-none">
                <div class="card border-0 shadow-sm rounded-4 mb-3 support-card whatsapp-card">
                    <div class="card-body p-3 d-flex align-items-center">
                        <div class="icon-wrapper bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                            <i class="bi bi-whatsapp fs-4"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold text-dark"><?php echo e(__('Chat on WhatsApp')); ?></h6>
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
                            <h6 class="mb-0 fw-bold text-dark"><?php echo e(__('Call Us Directly')); ?></h6>
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
                            <h6 class="mb-0 fw-bold text-dark"><?php echo e(__('Email Support')); ?></h6>
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
                            <h6 class="mb-0 fw-bold text-dark"><?php echo e(__('Instagram')); ?></h6>
                            <small class="fw-semibold" style="color: #e1306c;">@zpos.tz</small>
                        </div>
                        <i class="bi bi-chevron-right ms-auto text-muted"></i>
                    </div>
                </div>
            </a>
            
            <div class="mt-5 text-center">
                <div class="d-flex justify-content-center align-items-center opacity-50 mb-2">
                    <img src="<?php echo e(asset('images/logo_pos.png')); ?>" alt="Z-pos" style="width: 30px;">
                    <span class="fw-bold ms-2" style="font-size: 1.2rem; color: #0f172a;"><?php echo e(__('Z-pos')); ?></span>
                </div>
                <div class="text-muted small">&copy; <?php echo e(date('Y')); ?> Z-pos. All rights reserved.</div>
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
                timer: 3500,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                }
            });

            window.showToast = function(icon, title, timer = 3500) {
                Toast.fire({ icon, title, timer });
            };

            <?php if(session('success')): ?>
                Toast.fire({
                    icon: 'success',
                    title: "<?php echo e(addslashes(session('success'))); ?>"
                });
            <?php endif; ?>

            <?php if(session('error')): ?>
                Toast.fire({
                    icon: 'error',
                    title: "<?php echo e(addslashes(session('error'))); ?>"
                });
            <?php endif; ?>

            <?php if(session('status')): ?>
                Toast.fire({
                    icon: 'info',
                    title: "<?php echo e(addslashes(session('status'))); ?>"
                });
            <?php endif; ?>

            <?php if(session('warning')): ?>
                Toast.fire({
                    icon: 'warning',
                    title: "<?php echo e(addslashes(session('warning'))); ?>"
                });
            <?php endif; ?>

            <?php if($errors->any()): ?>
                Toast.fire({
                    icon: 'error',
                    title: "<?php echo e(addslashes($errors->first())); ?>"
                });
            <?php endif; ?>

            const sidebar = document.getElementById('sidebar');
            const content = document.getElementById('content');
            const overlay = document.getElementById('sidebarOverlay');
            const toggleBtn = document.getElementById('sidebarCollapse');

            function toggleSidebar() {
                if(sidebar) sidebar.classList.toggle('active');
                if (window.innerWidth <= 768) {
                    if(overlay) {
                        const isNowActive = sidebar && sidebar.classList.contains('active');
                        if (isNowActive) {
                            overlay.classList.add('active');
                            overlay.style.display = 'block';
                            overlay.style.pointerEvents = 'auto';
                        } else {
                            overlay.classList.remove('active');
                            overlay.style.display = 'none';
                            overlay.style.pointerEvents = 'none';
                        }
                    }
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
        const vapidPublicKey = "<?php echo e(env('VAPID_PUBLIC_KEY')); ?>";
        
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
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
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

        if ('serviceWorker' in navigator && 'PushManager' in window && 'Notification' in window) {
            navigator.serviceWorker.register('/sw.js?v=81')
            .then(function(swReg) {
                console.log('Service Worker is registered', swReg);
                if (Notification.permission === 'granted') {
                    subscribeUserToPush();
                }
            })
            .catch(function(error) {
                console.warn('Service Worker registration skipped or failed:', error);
            });
        }
    </script>
    

    <?php echo $__env->yieldPushContent('scripts'); ?>

    
    <script>
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                // Page was loaded from bfcache (back button) - force a fresh reload
                window.location.reload();
            }
        });

        // Ensure sidebar dropdowns always start closed on page load and refresh as requested
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('#sidebar .custom-submenu').forEach(function(menu) {
                menu.classList.remove('show');
            });
            document.querySelectorAll('#sidebar .dropdown-toggle').forEach(function(toggle) {
                toggle.setAttribute('aria-expanded', 'false');
            });
        });

        // Auto-search functionality using AJAX
        document.addEventListener('DOMContentLoaded', function() {
            const searchInputs = document.querySelectorAll('input[name="search"]');
            searchInputs.forEach(input => {
                let timer;
                const form = input.closest('form');
                
                // Auto-focus and place cursor at end if there's an active search
                if (input.value) {
                    input.focus();
                    const val = input.value;
                    input.value = '';
                    input.value = val;
                }

                input.addEventListener('input', function(e) {
                    clearTimeout(timer);

                    // Pressing the search button / Enter always searches, even if the text ends with a
                    // space (phone keyboards add one after a suggested word).
                    const forced = this.dataset.forceSearch === '1';
                    delete this.dataset.forceSearch;
                    if (forced) {
                        this.value = this.value.trim();
                    }

                    // Don't auto-submit if user just typed a space, let them finish the word
                    if (!forced && this.value.endsWith(' ')) {
                        return;
                    }

                    timer = setTimeout(() => {
                        const url = new URL(form.action);
                        const params = new URLSearchParams(new FormData(form));
                        params.set(input.name, input.value.trim());
                        url.search = params.toString();

                        // Add a loading indicator to the search icon if possible
                        const btn = form.querySelector('button[type="submit"] i');
                        const originalClass = btn ? btn.className : '';
                        if (btn) btn.className = 'bi bi-arrow-repeat spinner-border spinner-border-sm border-0';

                        fetch(url, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => response.text())
                        .then(html => {
                            const parser = new DOMParser();
                            const doc = parser.parseFromString(html, 'text/html');
                            
                            // Find the main content area (usually card-body containing table)
                            const currentTableArea = document.querySelector('.table-responsive');
                            const newTableArea = doc.querySelector('.table-responsive');
                            
                            if (currentTableArea && newTableArea) {
                                currentTableArea.innerHTML = newTableArea.innerHTML;
                                
                                // Also update pagination if it exists
                                const currentPagination = document.querySelector('.pagination')?.closest('div');
                                const newPagination = doc.querySelector('.pagination')?.closest('div');
                                
                                if (currentPagination && newPagination) {
                                    currentPagination.innerHTML = newPagination.innerHTML;
                                } else if (currentPagination && !newPagination) {
                                    currentPagination.innerHTML = '';
                                }
                                
                                // Update URL without reloading
                                window.history.pushState({}, '', url);
                            } else {
                                // Fallback to normal submit if structure is different
                                form.submit();
                            }
                            
                            if (btn) btn.className = originalClass;
                        })
                        .catch(() => {
                            // Fallback on error
                            form.submit();
                        });
                    }, 600); // 600ms debounce
                });
                
                // Handle normal form submit (e.g. hitting Enter)
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    input.dataset.forceSearch = '1';
                    input.dispatchEvent(new Event('input'));
                });
            });

            // Theme Switcher Logic (Scoped per Shop & Persisted to DB)
            const themeBtn = document.getElementById('themeToggleBtn');
            if (themeBtn) {
                const shopId = '<?php echo e($activeShopId); ?>';
                const serverTheme = '<?php echo e($shopTheme); ?>';
                const localTheme = shopId ? localStorage.getItem('zpos_theme_shop_' + shopId) : null;
                const currentTheme = localTheme || serverTheme || 'light';

                function applyThemeUI(theme, persistToServer = false) {
                    const moon = themeBtn.querySelector('.theme-icon-moon');
                    const sun = themeBtn.querySelector('.theme-icon-sun');
                    if (theme === 'dark') {
                        document.documentElement.setAttribute('data-theme', 'dark');
                        document.documentElement.classList.add('dark-mode');
                        document.body.classList.add('dark-mode');
                        if (moon) moon.classList.add('d-none');
                        if (sun) sun.classList.remove('d-none');
                    } else {
                        document.documentElement.setAttribute('data-theme', 'light');
                        document.documentElement.classList.remove('dark-mode');
                        document.body.classList.remove('dark-mode');
                        if (moon) moon.classList.remove('d-none');
                        if (sun) sun.classList.add('d-none');
                    }

                    if (shopId) {
                        localStorage.setItem('zpos_theme_shop_' + shopId, theme);
                    }

                    window.dispatchEvent(new CustomEvent('themeChanged', { detail: { theme: theme } }));

                    if (persistToServer) {
                        fetch('<?php echo e(route("shop.theme")); ?>', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                            },
                            body: JSON.stringify({ theme: theme })
                        }).catch(() => {});
                    }
                }

                applyThemeUI(currentTheme, false);

                themeBtn.addEventListener('click', function() {
                    const activeTheme = document.documentElement.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
                    applyThemeUI(activeTheme, true);
                });
            }
        });
    </script>

    <!-- Flatpickr Datepicker Bundle & Global Auto-Init (Format: DD/MM/YYYY) -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        if (typeof flatpickr === 'undefined') {
            document.write('<script src="<?php echo e(asset('vendor/flatpickr/flatpickr.min.js')); ?>"><\/script>');
        }
    </script>
    <script>
        (function() {
            function parseCustomDate(datestr) {
                if (!datestr) return null;
                if (datestr instanceof Date) return datestr;
                const str = String(datestr).trim();
                if (!str) return null;

                // Support DD/MM/YYYY or DD-MM-YYYY or DD.MM.YYYY
                const dmy = str.match(/^(\d{1,2})[\/\-\.](\d{1,2})[\/\-\.](\d{4})$/);
                if (dmy) {
                    const d = parseInt(dmy[1], 10);
                    const m = parseInt(dmy[2], 10) - 1;
                    const y = parseInt(dmy[3], 10);
                    const dt = new Date(y, m, d);
                    if (dt.getFullYear() === y && dt.getMonth() === m && dt.getDate() === d) {
                        return dt;
                    }
                }

                // Support YYYY-MM-DD or YYYY/MM/DD
                const ymd = str.match(/^(\d{4})[\/\-\.](\d{1,2})[\/\-\.](\d{1,2})$/);
                if (ymd) {
                    const y = parseInt(ymd[1], 10);
                    const m = parseInt(ymd[2], 10) - 1;
                    const d = parseInt(ymd[3], 10);
                    const dt = new Date(y, m, d);
                    if (dt.getFullYear() === y && dt.getMonth() === m && dt.getDate() === d) {
                        return dt;
                    }
                }

                const dt = new Date(str);
                return isNaN(dt.getTime()) ? null : dt;
            }

            function initGlobalDatepickers(root) {
                if (typeof flatpickr === 'undefined') return;
                const container = root || document;
                const dateInputs = container.querySelectorAll('input[type="date"], input.datepicker, input.flatpickr-date');
                
                dateInputs.forEach(function(el) {
                    if (el._flatpickr) return;

                    const origClasses = el.className || 'form-control';
                    const isReadonly = el.hasAttribute('readonly');
                    const isRequired = el.hasAttribute('required');
                    const minVal = el.getAttribute('min');
                    const maxVal = el.getAttribute('max');
                    const defaultVal = el.value || el.getAttribute('value');

                    const fp = flatpickr(el, {
                        dateFormat: "Y-m-d",
                        altInput: true,
                        altFormat: "d/m/Y",
                        allowInput: !isReadonly,
                        clickOpens: true,
                        minDate: minVal || undefined,
                        maxDate: maxVal || undefined,
                        defaultDate: defaultVal || undefined,
                        altInputClass: origClasses + ' flatpickr-alt-date',
                        locale: {
                            firstDayOfWeek: 1
                        },
                        parseDate: function(datestr) {
                            return parseCustomDate(datestr);
                        },
                        onReady: function(selectedDates, dateStr, instance) {
                            if (instance.altInput) {
                                if (el.style.cssText) {
                                    instance.altInput.style.cssText = el.style.cssText;
                                }
                                instance.altInput.placeholder = el.getAttribute('placeholder') || 'DD/MM/YYYY';
                                if (isRequired) instance.altInput.required = true;
                                if (isReadonly) {
                                    instance.altInput.readOnly = true;
                                } else {
                                    instance.altInput.removeAttribute('readonly');
                                }
                                if (el.id) {
                                    instance.altInput.id = el.id + '_display';
                                }

                                // When user manually edits date with keyboard, parse and update seamlessly
                                instance.altInput.addEventListener('blur', function() {
                                    const val = this.value.trim();
                                    if (!val) {
                                        instance.clear();
                                    } else {
                                        const parsed = parseCustomDate(val);
                                        if (parsed) {
                                            instance.setDate(parsed, true);
                                        } else if (instance.selectedDates.length > 0) {
                                            instance.setDate(instance.selectedDates[0], false);
                                        }
                                    }
                                });

                                instance.altInput.addEventListener('keydown', function(e) {
                                    if (e.key === 'Enter') {
                                        e.preventDefault();
                                        const val = this.value.trim();
                                        if (!val) {
                                            instance.clear();
                                        } else {
                                            const parsed = parseCustomDate(val);
                                            if (parsed) {
                                                instance.setDate(parsed, true);
                                            }
                                        }
                                        instance.close();
                                        this.blur();
                                    }
                                });
                            }
                        }
                    });

                    // Safeguarded synchronizer for external scripts that set el.value
                    let isSyncing = false;
                    const desc = Object.getOwnPropertyDescriptor(HTMLInputElement.prototype, 'value');
                    if (desc && desc.set) {
                        const origSet = desc.set;
                        const origGet = desc.get;
                        try {
                            Object.defineProperty(el, 'value', {
                                set: function(val) {
                                    origSet.call(this, val);
                                    if (this._flatpickr && !isSyncing) {
                                        const currentFpDate = this._flatpickr.selectedDates[0];
                                        const currentFpFormatted = currentFpDate ? this._flatpickr.formatDate(currentFpDate, 'Y-m-d') : '';
                                        if (val !== currentFpFormatted) {
                                            isSyncing = true;
                                            try {
                                                this._flatpickr.setDate(val || '', false);
                                            } catch(e) {}
                                            finally {
                                                isSyncing = false;
                                            }
                                        }
                                    }
                                },
                                get: origGet,
                                configurable: true
                            });
                        } catch(e) {}
                    }
                });
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', function() {
                    initGlobalDatepickers(document);
                });
            } else {
                initGlobalDatepickers(document);
            }

            // Observe dynamic additions (modals, ajax renders)
            if (typeof MutationObserver !== 'undefined' && document.body) {
                const dateObserver = new MutationObserver(function(mutations) {
                    mutations.forEach(function(mutation) {
                        if (mutation.addedNodes && mutation.addedNodes.length > 0) {
                            mutation.addedNodes.forEach(function(node) {
                                if (node.nodeType === 1) {
                                    if (node.matches && (node.matches('input[type="date"], input.datepicker, input.flatpickr-date'))) {
                                        initGlobalDatepickers(node.parentNode || document);
                                    } else if (node.querySelectorAll) {
                                        initGlobalDatepickers(node);
                                    }
                                }
                            });
                        }
                    });
                });
                dateObserver.observe(document.body, { childList: true, subtree: true });
            }
        })();
    </script>
</body>
</html>

<?php /**PATH E:\Z-pos\resources\views/layouts/admin.blade.php ENDPATH**/ ?>