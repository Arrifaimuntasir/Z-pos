@extends('layouts.admin')

@section('title', __('Executive Dashboard'))

@section('content')
<style>
    /* ==========================================================================
       ENTERPRISE POS/ERP EXECUTIVE DASHBOARD DESIGN SYSTEM
       Crafted with Senior-Grade Precision, Dual-Direction Live Pulse & Dark Mode
       ========================================================================== */
    :root {
        --dash-bg: #f8fafc;
        --dash-surface: #ffffff;
        --dash-border: #e2e8f0;
        --dash-border-subtle: #f1f5f9;
        --dash-text-main: #0f172a;
        --dash-text-muted: #64748b;
        --dash-text-light: #94a3b8;
        --dash-primary: #2563eb;
        --dash-primary-hover: #1d4ed8;
        --dash-success: #059669;
        --dash-warning: #d97706;
        --dash-danger: #dc2626;
        --dash-radius-lg: 16px;
        --dash-radius-md: 12px;
        --dash-radius-sm: 8px;
        --dash-shadow-sm: 0 1px 2px 0 rgba(15, 23, 42, 0.04);
        --dash-shadow-card: 0 1px 3px 0 rgba(15, 23, 42, 0.05), 0 1px 2px -1px rgba(15, 23, 42, 0.03);
        --dash-shadow-hover: 0 8px 24px -4px rgba(15, 23, 42, 0.08), 0 4px 8px -2px rgba(15, 23, 42, 0.03);
    }

    [data-theme="dark"], body.dark-mode {
        --dash-bg: #0b1120;
        --dash-surface: #111827;
        --dash-border: #1f2937;
        --dash-border-subtle: #1e293b;
        --dash-text-main: #ffffff;
        --dash-text-muted: #94a3b8;
        --dash-text-light: #64748b;
        --dash-shadow-card: 0 1px 3px 0 rgba(0, 0, 0, 0.4);
        --dash-shadow-hover: 0 8px 24px -4px rgba(0, 0, 0, 0.5);
    }

    .dashboard-container {
        padding: 0;
        color: var(--dash-text-main);
        font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }

    /* Tabular figures for numbers alignment */
    .num-tabular {
        font-variant-numeric: tabular-nums;
        letter-spacing: -0.02em;
    }

    /* ===== 1. EXECUTIVE HEADER & CONTROLS ===== */
    .exec-header {
        background: var(--dash-surface);
        border: 1px solid var(--dash-border);
        border-radius: var(--dash-radius-lg);
        padding: 18px 24px;
        margin-bottom: 20px;
        box-shadow: var(--dash-shadow-card);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
    }
    .exec-title-group h3 {
        font-size: 1.35rem;
        font-weight: 800;
        color: var(--dash-text-main);
        margin: 0;
        letter-spacing: -0.03em;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .shop-badge {
        font-size: 0.72rem;
        font-weight: 700;
        background: #f1f5f9;
        color: #334155;
        border: 1px solid #e2e8f0;
        padding: 3px 9px;
        border-radius: var(--dash-radius-sm);
        letter-spacing: 0.3px;
        text-transform: uppercase;
    }
    .exec-meta-subtitle {
        font-size: 0.8rem;
        color: var(--dash-text-muted);
        margin-top: 4px;
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }
    .status-live-dot {
        width: 7px;
        height: 7px;
        background: var(--dash-success);
        border-radius: 50%;
        display: inline-block;
        box-shadow: 0 0 0 2px rgba(5, 150, 105, 0.2);
    }

    .exec-controls {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }
    .exec-select {
        height: 40px;
        padding: 0 14px;
        border-radius: var(--dash-radius-md);
        border: 1px solid var(--dash-border);
        background: #ffffff;
        font-size: 0.83rem;
        font-weight: 600;
        color: #1e293b;
        cursor: pointer;
        outline: none;
        transition: all 0.15s ease;
        box-shadow: var(--dash-shadow-sm);
    }
    .exec-select:hover {
        border-color: #cbd5e1;
    }
    .exec-select:focus {
        border-color: var(--dash-primary);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
    }
    .exec-action-btn {
        height: 40px;
        padding: 0 14px;
        border-radius: var(--dash-radius-md);
        border: 1px solid var(--dash-border);
        background: #ffffff;
        font-size: 0.82rem;
        font-weight: 600;
        color: #334155;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
        transition: all 0.15s ease;
        box-shadow: var(--dash-shadow-sm);
    }
    .exec-action-btn:hover {
        background: #f8fafc;
        color: var(--dash-text-main);
        border-color: #cbd5e1;
    }

    /* Custom Date Range Controls */
    .exec-custom-range-bar {
        background: var(--dash-surface);
        border: 1px solid var(--dash-border);
        border-radius: var(--dash-radius-md);
        padding: 12px 18px;
        box-shadow: var(--dash-shadow-sm);
    }
    .exec-date-input {
        background: var(--dash-surface);
        border: 1.5px solid var(--dash-border);
        color: var(--dash-text-main);
        border-radius: var(--dash-radius-sm);
        font-size: 0.82rem;
        font-weight: 600;
        padding: 6px 12px;
        height: 38px;
        transition: border-color 0.15s ease;
    }
    .exec-date-input:focus {
        border-color: var(--dash-primary);
        outline: none;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
    }

    /* Filter Active Banner */
    .filter-banner-strip {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-left: 4px solid var(--dash-primary);
        border-radius: var(--dash-radius-md);
        padding: 10px 18px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
    }
    .filter-badge-avatar {
        width: 28px;
        height: 28px;
        border-radius: 8px;
        background: #0f172a;
        color: #ffffff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 0.78rem;
    }

    /* ===== 2. QUICK OPERATIONS BAR (Live Pulse Smooth Infinite Marquee) ===== */
    .pulse-marquee-wrapper {
        width: 100% !important;
        max-width: 100% !important;
        overflow: hidden !important;
        position: relative;
        padding: 4px 0 10px 0;
        touch-action: pan-y;
        -webkit-overflow-scrolling: touch;
    }
    .pulse-marquee-wrapper::before,
    .pulse-marquee-wrapper::after {
        content: "";
        position: absolute;
        top: 0;
        bottom: 0;
        width: 32px;
        z-index: 6;
        pointer-events: none !important;
    }
    .pulse-marquee-wrapper::before {
        left: 0;
        background: linear-gradient(to right, var(--dash-bg, #f8fafc), transparent);
    }
    .pulse-marquee-wrapper::after {
        right: 0;
        background: linear-gradient(to left, var(--dash-bg, #f8fafc), transparent);
    }
    [data-theme="dark"] .pulse-marquee-wrapper::before,
    body.dark-mode .pulse-marquee-wrapper::before {
        background: linear-gradient(to right, var(--dash-bg, #0b1120), transparent);
    }
    [data-theme="dark"] .pulse-marquee-wrapper::after,
    body.dark-mode .pulse-marquee-wrapper::after {
        background: linear-gradient(to left, var(--dash-bg, #0b1120), transparent);
    }
    .pulse-track {
        display: flex;
        gap: 14px;
        width: max-content;
        animation: pulseTrackSlide 30s linear infinite;
        will-change: transform;
    }
    .pulse-track:hover,
    .pulse-track:active,
    .pulse-track.is-paused {
        animation-play-state: paused !important;
    }
    @keyframes pulseTrackSlide {
        0% { transform: translateX(0); }
        100% { transform: translateX(-50%); }
    }
    .quick-op-item {
        flex-shrink: 0;
    }
    .quick-op-btn {
        background: var(--dash-surface);
        border: 1.5px solid var(--dash-border);
        border-radius: var(--dash-radius-md);
        padding: 12px 18px;
        display: flex;
        align-items: center;
        gap: 12px;
        text-decoration: none;
        color: var(--dash-text-main);
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: var(--dash-shadow-card);
        width: 220px;
        min-width: 220px;
        box-sizing: border-box;
        cursor: pointer;
        position: relative;
        z-index: 5;
        touch-action: manipulation;
        -webkit-tap-highlight-color: transparent;
        user-select: none;
        -webkit-user-select: none;
    }
    .quick-op-btn:hover {
        transform: translateY(-2px);
        box-shadow: var(--dash-shadow-hover);
        border-color: #cbd5e1;
        color: var(--dash-primary);
    }
    .quick-op-btn.primary-pos {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border-color: #0f172a;
        color: #ffffff;
    }
    .quick-op-btn.primary-pos:hover {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        border-color: #1d4ed8;
        color: #ffffff;
    }
    .quick-op-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.15rem;
        flex-shrink: 0;
        transition: transform 0.2s ease;
    }
    .quick-op-btn:hover .quick-op-icon {
        transform: scale(1.08);
    }

    /* Light Mode Quick Op Icons: Bold, High Contrast & 100% Visible */
    .quick-op-icon.qoi-dark { background: rgba(255, 255, 255, 0.2); color: #ffffff; }
    .quick-op-icon.qoi-dark i { color: #ffffff !important; }

    .quick-op-icon.qoi-blue { background: #dbeafe; color: #1d4ed8; border: 1px solid #bfdbfe; }
    .quick-op-icon.qoi-blue i { color: #1d4ed8 !important; font-size: 1.15rem !important; }

    .quick-op-icon.qoi-green { background: #d1fae5; color: #047857; border: 1px solid #a7f3d0; }
    .quick-op-icon.qoi-green i { color: #047857 !important; font-size: 1.15rem !important; }

    .quick-op-icon.qoi-amber { background: #fef3c7; color: #b45309; border: 1px solid #fde68a; }
    .quick-op-icon.qoi-amber i { color: #b45309 !important; font-size: 1.15rem !important; }

    .quick-op-icon.qoi-purple { background: #ede9fe; color: #6d28d9; border: 1px solid #ddd6fe; }
    .quick-op-icon.qoi-purple i { color: #6d28d9 !important; font-size: 1.15rem !important; }

    .quick-op-texts {
        display: flex;
        flex-direction: column;
        line-height: 1.25;
        min-width: 0;
    }
    .quick-op-label {
        font-size: 0.82rem;
        font-weight: 700;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .quick-op-sub {
        font-size: 0.69rem;
        color: var(--dash-text-muted);
    }
    .primary-pos .quick-op-sub { color: rgba(255, 255, 255, 0.7); }

    /* ===== 3. STATIC FINANCIAL KPI CARDS (Exact match to User Reference Image) ===== */
    .kpi-card {
        border-radius: 18px;
        padding: 20px 22px;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);
    }
    .kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(15, 23, 42, 0.08);
    }
    .kpi-card-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 12px;
    }
    .kpi-card-title {
        font-size: 0.78rem;
        font-weight: 800;
        letter-spacing: 0.05em;
        text-transform: uppercase;
    }
    .kpi-card-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: #ffffff;
        box-shadow: 0 2px 6px rgba(15, 23, 42, 0.08);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }
    .kpi-card-value {
        font-size: 1.85rem;
        font-weight: 800;
        line-height: 1.15;
        letter-spacing: -0.03em;
        color: #0f172a;
        margin-bottom: 16px;
        display: flex;
        align-items: baseline;
        gap: 6px;
    }
    .kpi-card-unit {
        font-size: 0.85rem;
        font-weight: 600;
        color: #64748b;
    }
    .kpi-card-footer {
        border-top: 1px solid rgba(0, 0, 0, 0.05);
        padding-top: 12px;
        margin-top: auto;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .kpi-footer-label {
        font-size: 0.82rem;
        font-weight: 600;
        color: #64748b;
    }
    .kpi-badge {
        padding: 3px 12px;
        border-radius: 8px;
        font-size: 0.76rem;
        font-weight: 700;
        background: #ffffff;
    }

    /* Light Mode Exact Themes (from User Reference Image) */
    .kpi-card-blue {
        background: #f0f7ff;
        border: 1.5px solid #dbeafe;
    }
    .kpi-card-blue .kpi-card-title { color: #2563eb; }
    .kpi-card-blue .kpi-card-icon { color: #3b82f6; }
    .kpi-card-blue .kpi-badge {
        color: #1d4ed8;
        border: 1.5px solid #bfdbfe;
    }

    .kpi-card-green {
        background: #f0fdf4;
        border: 1.5px solid #dcfce7;
    }
    .kpi-card-green .kpi-card-title { color: #16a34a; }
    .kpi-card-green .kpi-card-icon { color: #16a34a; }
    .kpi-card-green .kpi-badge {
        color: #059669;
        border: 1.5px solid #a7f3d0;
    }

    .kpi-card-amber {
        background: #fefce8;
        border: 1.5px solid #fef08a;
    }
    .kpi-card-amber .kpi-card-title { color: #d97706; }
    .kpi-card-amber .kpi-card-icon { color: #d97706; }
    .kpi-card-amber .kpi-badge {
        color: #b45309;
        border: 1.5px solid #fde68a;
    }

    .kpi-card-rose {
        background: #fff1f2;
        border: 1.5px solid #ffe4e6;
    }
    .kpi-card-rose .kpi-card-title { color: #dc2626; }
    .kpi-card-rose .kpi-card-icon { color: #dc2626; }
    .kpi-card-rose .kpi-badge {
        color: #be123c;
        border: 1.5px solid #fecdd3;
    }

    /* Net Profit Master Card */
    .net-profit-hero {
        background: var(--dash-surface);
        border: 1px solid var(--dash-border);
        border-radius: var(--dash-radius-lg);
        padding: 20px 24px;
        box-shadow: var(--dash-shadow-card);
        margin-bottom: 22px;
        position: relative;
        overflow: hidden;
    }
    .net-profit-hero.is-profit {
        border-left: 5px solid var(--dash-success);
        background: linear-gradient(180deg, #ffffff 0%, #f0fdf4 100%);
    }
    .net-profit-hero.is-loss {
        border-left: 5px solid var(--dash-danger);
        background: linear-gradient(180deg, #ffffff 0%, #fef2f2 100%);
    }

    /* ===== CHARTS & DATA VISUALIZATION CARDS ===== */
    .dash-card {
        background: var(--dash-surface);
        border: 1px solid var(--dash-border);
        border-radius: var(--dash-radius-lg);
        padding: 22px 24px;
        box-shadow: var(--dash-shadow-card);
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .dash-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 18px;
        flex-wrap: wrap;
        gap: 10px;
    }
    .dash-card-title {
        font-size: 0.95rem;
        font-weight: 800;
        color: var(--dash-text-main);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
        letter-spacing: -0.01em;
    }
    .dash-card-badge {
        font-size: 0.72rem;
        font-weight: 600;
        background: #f1f5f9;
        color: #475569;
        padding: 4px 10px;
        border-radius: 20px;
        border: 1px solid #e2e8f0;
    }

    /* Summary bar below chart */
    .chart-summary-bar {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
        gap: 12px;
        padding-top: 16px;
        margin-top: auto;
        border-top: 1px solid var(--dash-border-subtle);
    }
    .csb-item {
        display: flex;
        flex-direction: column;
    }
    .csb-label {
        font-size: 0.7rem;
        font-weight: 700;
        color: var(--dash-text-muted);
        text-transform: uppercase;
        letter-spacing: 0.03em;
        margin-bottom: 3px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .csb-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
    }
    .csb-val {
        font-size: 1rem;
        font-weight: 800;
        color: var(--dash-text-main);
    }

    /* ===== RECENT TRANSACTIONS TABLE ===== */
    .exec-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }
    .exec-table th {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--dash-text-muted);
        background: #f8fafc;
        padding: 10px 14px;
        border-bottom: 1px solid var(--dash-border);
        white-space: nowrap;
    }
    .exec-table td {
        font-size: 0.82rem;
        padding: 12px 14px;
        border-bottom: 1px solid var(--dash-border-subtle);
        vertical-align: middle;
        color: #334155;
    }
    .exec-table tbody tr:hover td {
        background: #f8fafc;
    }
    .exec-table tbody tr:last-child td {
        border-bottom: none;
    }
    .ref-code-badge {
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        font-size: 0.76rem;
        font-weight: 700;
        color: #1e293b;
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        padding: 2px 7px;
        border-radius: 6px;
    }

    /* Status Pills */
    .badge-paid {
        background: #ecfdf5;
        color: #065f46;
        border: 1px solid #a7f3d0;
        padding: 3px 9px;
        border-radius: 20px;
        font-size: 0.72rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .badge-partial {
        background: #fffbeb;
        color: #92400e;
        border: 1px solid #fde68a;
        padding: 3px 9px;
        border-radius: 20px;
        font-size: 0.72rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .badge-unpaid {
        background: #fef2f2;
        color: #991b1b;
        border: 1px solid #fecdd3;
        padding: 3px 9px;
        border-radius: 20px;
        font-size: 0.72rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    /* Top Products Leaderboard */
    .top-product-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 0;
        border-bottom: 1px solid var(--dash-border-subtle);
    }
    .top-product-item:last-child {
        border-bottom: none;
    }
    .top-rank-badge {
        width: 26px;
        height: 26px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.74rem;
        font-weight: 800;
        flex-shrink: 0;
    }
    .rank-1 { background: #fef3c7; color: #b45309; border: 1px solid #fde68a; }
    .rank-2 { background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }
    .rank-3 { background: #ffedd5; color: #c2410c; border: 1px solid #fed7aa; }
    .rank-other { background: #f8fafc; color: #64748b; border: 1px solid #f1f5f9; }

    .tpi-progress {
        height: 4px;
        background: #f1f5f9;
        border-radius: 2px;
        overflow: hidden;
        margin-top: 5px;
    }
    .tpi-progress-bar {
        height: 100%;
        background: var(--dash-primary);
        border-radius: 2px;
    }

    /* ==========================================================================
       HIGH CONTRAST DARK MODE - PURE WHITE TEXT (NO GRAY)
       ========================================================================== */
    [data-theme="dark"], body.dark-mode {
        --dash-bg: #0b1120;
        --dash-surface: #111827;
        --dash-border: #1f2937;
        --dash-border-subtle: #1e293b;
        --dash-text-main: #ffffff;
        --dash-text-muted: #ffffff;
        --dash-text-light: #ffffff;
        --dash-shadow-card: 0 1px 3px 0 rgba(0, 0, 0, 0.4);
        --dash-shadow-hover: 0 8px 24px -4px rgba(0, 0, 0, 0.5);
    }

    /* ALL DASHBOARD TEXT IS PURE WHITE IN DARK MODE */
    [data-theme="dark"] .dashboard-container,
    body.dark-mode .dashboard-container {
        color: #ffffff;
    }
    [data-theme="dark"] h1, [data-theme="dark"] h2, [data-theme="dark"] h3,
    [data-theme="dark"] h4, [data-theme="dark"] h5, [data-theme="dark"] h6,
    [data-theme="dark"] p, [data-theme="dark"] label,
    [data-theme="dark"] strong, [data-theme="dark"] small,
    [data-theme="dark"] .text-dark,
    body.dark-mode .text-dark,
    [data-theme="dark"] strong.text-dark,
    body.dark-mode strong.text-dark,
    [data-theme="dark"] .fw-bold.text-dark,
    body.dark-mode .fw-bold.text-dark,
    [data-theme="dark"] .fw-semibold.text-dark,
    body.dark-mode .fw-semibold.text-dark,
    [data-theme="dark"] .text-muted,
    body.dark-mode .text-muted,
    [data-theme="dark"] small.text-muted,
    body.dark-mode small.text-muted,
    [data-theme="dark"] .exec-meta-subtitle,
    body.dark-mode .exec-meta-subtitle,
    [data-theme="dark"] .exec-meta-subtitle strong,
    body.dark-mode .exec-meta-subtitle strong {
        color: #ffffff !important;
    }

    /* KPI Cards in Dark Mode */
    [data-theme="dark"] .kpi-card,
    body.dark-mode .kpi-card {
        background: #111827 !important;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.4) !important;
    }
    [data-theme="dark"] .kpi-card-blue,
    body.dark-mode .kpi-card-blue {
        border: 1.5px solid #2563eb !important;
    }
    [data-theme="dark"] .kpi-card-green,
    body.dark-mode .kpi-card-green {
        border: 1.5px solid #059669 !important;
    }
    [data-theme="dark"] .kpi-card-amber,
    body.dark-mode .kpi-card-amber {
        border: 1.5px solid #d97706 !important;
    }
    [data-theme="dark"] .kpi-card-rose,
    body.dark-mode .kpi-card-rose {
        border: 1.5px solid #dc2626 !important;
    }

    [data-theme="dark"] .kpi-card-title,
    body.dark-mode .kpi-card-title,
    [data-theme="dark"] .kpi-card-value,
    body.dark-mode .kpi-card-value,
    [data-theme="dark"] .kpi-card-unit,
    body.dark-mode .kpi-card-unit,
    [data-theme="dark"] .kpi-footer-label,
    body.dark-mode .kpi-footer-label,
    [data-theme="dark"] .kpi-badge,
    body.dark-mode .kpi-badge {
        color: #ffffff !important;
    }

    [data-theme="dark"] .kpi-card-icon,
    body.dark-mode .kpi-card-icon {
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3) !important;
    }
    [data-theme="dark"] .kpi-card-blue .kpi-card-icon,
    body.dark-mode .kpi-card-blue .kpi-card-icon {
        background: rgba(37, 99, 235, 0.25) !important;
        border: 1.5px solid rgba(96, 165, 250, 0.5) !important;
    }
    [data-theme="dark"] .kpi-card-blue .kpi-card-icon i,
    body.dark-mode .kpi-card-blue .kpi-card-icon i { color: #60a5fa !important; }

    [data-theme="dark"] .kpi-card-green .kpi-card-icon,
    body.dark-mode .kpi-card-green .kpi-card-icon {
        background: rgba(16, 185, 129, 0.25) !important;
        border: 1.5px solid rgba(52, 211, 153, 0.5) !important;
    }
    [data-theme="dark"] .kpi-card-green .kpi-card-icon i,
    body.dark-mode .kpi-card-green .kpi-card-icon i { color: #34d399 !important; }

    [data-theme="dark"] .kpi-card-amber .kpi-card-icon,
    body.dark-mode .kpi-card-amber .kpi-card-icon {
        background: rgba(245, 158, 11, 0.25) !important;
        border: 1.5px solid rgba(251, 191, 36, 0.5) !important;
    }
    [data-theme="dark"] .kpi-card-amber .kpi-card-icon i,
    body.dark-mode .kpi-card-amber .kpi-card-icon i { color: #fbbf24 !important; }

    [data-theme="dark"] .kpi-card-rose .kpi-card-icon,
    body.dark-mode .kpi-card-rose .kpi-card-icon {
        background: rgba(220, 38, 38, 0.25) !important;
        border: 1.5px solid rgba(248, 113, 113, 0.5) !important;
    }
    [data-theme="dark"] .kpi-card-rose .kpi-card-icon i,
    body.dark-mode .kpi-card-rose .kpi-card-icon i { color: #f87171 !important; }

    [data-theme="dark"] .kpi-card-footer,
    body.dark-mode .kpi-card-footer {
        border-top: 1px solid #1f2937 !important;
    }
    [data-theme="dark"] .kpi-badge,
    body.dark-mode .kpi-badge {
        background: #1e293b !important;
        border-color: #374151 !important;
    }

    /* Quick Operations & Dash Cards in Dark Mode */
    [data-theme="dark"] .quick-op-btn:not(.primary-pos),
    body.dark-mode .quick-op-btn:not(.primary-pos) {
        background-color: #111827 !important;
        border-color: #1f2937 !important;
        color: #ffffff !important;
    }
    [data-theme="dark"] .quick-op-btn:hover,
    body.dark-mode .quick-op-btn:hover {
        border-color: #3b82f6 !important;
    }
    [data-theme="dark"] .quick-op-label,
    body.dark-mode .quick-op-label,
    [data-theme="dark"] .quick-op-sub,
    body.dark-mode .quick-op-sub {
        color: #ffffff !important;
    }

    /* Dark Mode Icons - Deep Tinted Backgrounds + Bright Glowing Crisp Icons */
    [data-theme="dark"] .quick-op-icon.qoi-dark,
    body.dark-mode .quick-op-icon.qoi-dark {
        background: rgba(255, 255, 255, 0.15) !important;
        border: 1px solid rgba(255, 255, 255, 0.25) !important;
    }
    [data-theme="dark"] .quick-op-icon.qoi-dark i,
    body.dark-mode .quick-op-icon.qoi-dark i {
        color: #ffffff !important;
    }

    [data-theme="dark"] .quick-op-icon.qoi-blue,
    body.dark-mode .quick-op-icon.qoi-blue {
        background: rgba(37, 99, 235, 0.25) !important;
        border: 1.5px solid rgba(96, 165, 250, 0.5) !important;
    }
    [data-theme="dark"] .quick-op-icon.qoi-blue i,
    body.dark-mode .quick-op-icon.qoi-blue i {
        color: #60a5fa !important;
    }

    [data-theme="dark"] .quick-op-icon.qoi-green,
    body.dark-mode .quick-op-icon.qoi-green {
        background: rgba(16, 185, 129, 0.25) !important;
        border: 1.5px solid rgba(52, 211, 153, 0.5) !important;
    }
    [data-theme="dark"] .quick-op-icon.qoi-green i,
    body.dark-mode .quick-op-icon.qoi-green i {
        color: #34d399 !important;
    }

    [data-theme="dark"] .quick-op-icon.qoi-amber,
    body.dark-mode .quick-op-icon.qoi-amber {
        background: rgba(245, 158, 11, 0.25) !important;
        border: 1.5px solid rgba(251, 191, 36, 0.5) !important;
    }
    [data-theme="dark"] .quick-op-icon.qoi-amber i,
    body.dark-mode .quick-op-icon.qoi-amber i {
        color: #fbbf24 !important;
    }

    [data-theme="dark"] .quick-op-icon.qoi-purple,
    body.dark-mode .quick-op-icon.qoi-purple {
        background: rgba(168, 85, 247, 0.25) !important;
        border: 1.5px solid rgba(192, 132, 252, 0.5) !important;
    }
    [data-theme="dark"] .quick-op-icon.qoi-purple i,
    body.dark-mode .quick-op-icon.qoi-purple i {
        color: #c084fc !important;
    }

    /* Guarantee visibility for all icons across dark mode */
    [data-theme="dark"] i.bi,
    body.dark-mode i.bi,
    [data-theme="dark"] .bi,
    body.dark-mode .bi {
        opacity: 1 !important;
    }
    [data-theme="dark"] .dash-card-title,
    body.dark-mode .dash-card-title,
    [data-theme="dark"] .csb-val,
    body.dark-mode .csb-val,
    [data-theme="dark"] .csb-label,
    body.dark-mode .csb-label {
        color: #ffffff !important;
    }
    [data-theme="dark"] .dash-card-badge,
    body.dark-mode .dash-card-badge {
        background-color: #1e293b !important;
        border: 1px solid #334155 !important;
        color: #ffffff !important;
    }
    [data-theme="dark"] .csb-val small,
    body.dark-mode .csb-val small {
        color: #cbd5e1 !important;
    }

    /* Modern Dark Link Button */
    .dash-card-link-btn {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #334155;
        font-size: 0.75rem;
        font-weight: 600;
        padding: 5px 14px;
        border-radius: 20px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: all 0.15s ease;
    }
    .dash-card-link-btn:hover {
        background: #e2e8f0;
        color: #0f172a;
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

    /* APEXCHARTS HIGH CONTRAST DARK MODE ENGINE (SVG Uses fill, not color) */
    [data-theme="dark"] .apexcharts-canvas text,
    body.dark-mode .apexcharts-canvas text,
    [data-theme="dark"] .apexcharts-canvas text.apexcharts-text,
    body.dark-mode .apexcharts-canvas text.apexcharts-text {
        fill: #cbd5e1 !important;
    }
    [data-theme="dark"] .apexcharts-canvas text.apexcharts-datalabel-value,
    body.dark-mode .apexcharts-canvas text.apexcharts-datalabel-value {
        fill: #ffffff !important;
        font-weight: 800 !important;
    }
    [data-theme="dark"] .apexcharts-canvas text.apexcharts-datalabel-label,
    body.dark-mode .apexcharts-canvas text.apexcharts-datalabel-label {
        fill: #cbd5e1 !important;
        font-weight: 600 !important;
    }
    [data-theme="dark"] .apexcharts-legend-text,
    body.dark-mode .apexcharts-legend-text {
        color: #ffffff !important;
    }
    [data-theme="dark"] .apexcharts-tooltip,
    body.dark-mode .apexcharts-tooltip {
        background: #111827 !important;
        color: #ffffff !important;
        border: 1px solid #374151 !important;
    }
    [data-theme="dark"] .apexcharts-tooltip-title,
    body.dark-mode .apexcharts-tooltip-title {
        background: #1e293b !important;
        color: #ffffff !important;
    }
    [data-theme="dark"] .exec-select,
    body.dark-mode .exec-select {
        background-color: #111827 !important;
        border-color: #374151 !important;
        color: #ffffff !important;
    }
    [data-theme="dark"] .exec-select option,
    body.dark-mode .exec-select option {
        background-color: #111827 !important;
        color: #ffffff !important;
    }
    [data-theme="dark"] .exec-action-btn,
    body.dark-mode .exec-action-btn {
        background-color: #111827 !important;
        border-color: #374151 !important;
        color: #ffffff !important;
    }
    [data-theme="dark"] .exec-custom-range-bar,
    body.dark-mode .exec-custom-range-bar {
        background-color: #111827 !important;
        border-color: #1f2937 !important;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.4) !important;
    }
    [data-theme="dark"] .exec-date-input,
    body.dark-mode .exec-date-input {
        background-color: #1e293b !important;
        border-color: #334155 !important;
        color: #ffffff !important;
        color-scheme: dark;
    }
    [data-theme="dark"] .filter-banner-strip,
    body.dark-mode .filter-banner-strip {
        background-color: #111827 !important;
        border-color: #1f2937 !important;
        color: #ffffff !important;
    }
    [data-theme="dark"] .shop-badge,
    body.dark-mode .shop-badge {
        background: #1e293b !important;
        border-color: #334155 !important;
        color: #ffffff !important;
    }
    [data-theme="dark"] .exec-table th,
    body.dark-mode .exec-table th {
        background-color: #0f172a !important;
        color: #ffffff !important;
        border-bottom-color: #1f2937 !important;
    }
    [data-theme="dark"] .exec-table td,
    body.dark-mode .exec-table td {
        background-color: transparent !important;
        border-bottom-color: #1f2937 !important;
        color: #ffffff !important;
    }
    [data-theme="dark"] .exec-table tbody tr:hover td,
    body.dark-mode .exec-table tbody tr:hover td {
        background-color: #1f2937 !important;
        color: #ffffff !important;
    }
    [data-theme="dark"] .ref-code-badge,
    body.dark-mode .ref-code-badge {
        background: #1e293b !important;
        border-color: #334155 !important;
        color: #ffffff !important;
    }
    [data-theme="dark"] .btn-light,
    body.dark-mode .btn-light {
        background-color: #1e293b !important;
        border-color: #334155 !important;
        color: #ffffff !important;
    }
    [data-theme="dark"] .top-rank-badge.rank-2,
    [data-theme="dark"] .top-rank-badge.rank-other,
    body.dark-mode .top-rank-badge.rank-2,
    body.dark-mode .top-rank-badge.rank-other {
        background: #1e293b !important;
        border-color: #334155 !important;
        color: #ffffff !important;
    }
    [data-theme="dark"] .tpi-progress,
    body.dark-mode .tpi-progress {
        background-color: #1e293b !important;
    }
    [data-theme="dark"] .net-profit-hero.is-profit,
    body.dark-mode .net-profit-hero.is-profit {
        background: linear-gradient(180deg, #111827 0%, rgba(6, 95, 70, 0.25) 100%) !important;
        border-color: #1f2937 !important;
    }
    [data-theme="dark"] .net-profit-hero.is-loss,
    body.dark-mode .net-profit-hero.is-loss {
        background: linear-gradient(180deg, #111827 0%, rgba(153, 27, 27, 0.25) 100%) !important;
        border-color: #1f2937 !important;
    }

    /* Responsiveness & Zero-Overflow Engine */
    .dashboard-container {
        width: 100% !important;
        max-width: 100% !important;
        overflow-x: hidden !important;
    }
    .pulse-section {
        width: 100% !important;
        max-width: 100% !important;
        overflow: hidden !important;
    }
    .pulse-marquee-wrapper {
        width: 100% !important;
        max-width: 100% !important;
        overflow: hidden !important;
        box-sizing: border-box !important;
    }
    @media (max-width: 991px) {
        .exec-header { padding: 14px 18px; }
        .metric-main-value { font-size: 1.45rem; }
        .dash-card { padding: 16px 18px; }
    }
    @media (max-width: 768px) {
        .net-profit-hero { padding: 16px; }
        .net-profit-hero .num-tabular { font-size: 1.45rem !important; }
        .quick-op-btn { width: 195px; min-width: 195px; padding: 10px 12px; }
        .metric-card { width: 245px; min-width: 245px; padding: 16px; }
    }
    .exec-controls-row {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }
    @media (max-width: 576px) {
        .exec-header {
            flex-direction: column;
            align-items: stretch;
            gap: 14px;
            padding: 15px 16px;
            border-radius: 14px;
        }
        .exec-title-group h3 {
            font-size: 1.18rem;
            margin-bottom: 2px;
        }
        .exec-meta-subtitle {
            font-size: 0.78rem;
            gap: 8px;
        }
        .exec-controls {
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .exec-controls-row {
            display: flex;
            align-items: center;
            gap: 8px;
            width: 100%;
        }
        .exec-select {
            width: 100%;
            font-size: 0.82rem;
            height: 42px;
            padding: 0 12px;
            border-radius: 10px;
        }
        .exec-controls-row .exec-select {
            flex: 1 1 auto;
            min-width: 0;
        }
        .exec-action-btn {
            height: 42px;
            padding: 0 16px;
            flex: 0 0 auto;
            justify-content: center;
            font-weight: 700;
            border-radius: 10px;
            white-space: nowrap;
        }
        .chart-summary-bar {
            grid-template-columns: 1fr 1fr;
        }
    }
</style>

<div class="dashboard-container">

    {{-- ========================================================================
         1. EXECUTIVE HEADER
         ======================================================================== --}}
    <div class="exec-header">
        <div class="exec-title-group">
            <h3>
                @if(auth()->user()->shop)
                    <span>{{ auth()->user()->shop->name }}</span>
                @else
                    <span>{{ config('app.name', 'Z-pos') }}</span>
                @endif
            </h3>
            <div class="exec-meta-subtitle">
                <span>{{ date('l, d F Y') }}</span>
                @if(session('active_branch_name'))
                <span class="d-none d-sm-inline">
                    &bull; {{ __('Branch') }}: <strong>{{ session('active_branch_name') }}</strong>
                </span>
                @endif
            </div>
        </div>

        <div class="exec-controls">
            {{-- Staff / Cashier Switcher (Administrator / Super Admin Only) --}}
            @if($isAdmin && $cashiers->isNotEmpty())
            <select id="cashierFilter" class="exec-select" onchange="window.location.href='?cashier_id='+this.value">
                <option value="all" {{ !$selectedCashierId ? 'selected' : '' }}>
                    👥 {{ __('All Staff Overview') }}
                </option>
                @foreach($cashiers as $c)
                @php $isOwn = $c->id === auth()->id() || $c->hasRole('Administrator'); @endphp
                <option value="{{ $c->id }}" {{ $selectedCashierId == $c->id ? 'selected' : '' }}>
                    {{ $isOwn ? '👑' : '💼' }} {{ $c->name }} ({{ $isOwn ? __('Owner') : __('Cashier') }})
                </option>
                @endforeach
            </select>
            @endif

            <div class="exec-controls-row">
                {{-- Period Filter --}}
                <select id="dashboardFilter" class="exec-select" onchange="handleDashboardPeriodChange(this.value)">
                    <option value="overall"   {{ $filter == 'overall'   ? 'selected' : '' }}>📊 {{ __('All-Time Data') }}</option>
                    <option value="today"     {{ $filter == 'today'     ? 'selected' : '' }}>📅 {{ __("Today's Analytics") }}</option>
                    <option value="month"     {{ $filter == 'month'     ? 'selected' : '' }}>📆 {{ __("This Month") }}</option>
                    <option value="half_year" {{ $filter == 'half_year' ? 'selected' : '' }}>📈 {{ __('Last 6 Months') }}</option>
                    <option value="year"      {{ $filter == 'year'      ? 'selected' : '' }}>🗓️ {{ __('This Year') }}</option>
                    <option value="custom"    {{ $filter == 'custom'    ? 'selected' : '' }}>🎯 {{ __('Custom Date Range') }}</option>
                </select>

                {{-- Quick Reports Link --}}
                <a href="{{ route('reports.index') }}" class="exec-action-btn" title="{{ __('View Comprehensive Reports') }}">
                    <i class="bi bi-file-earmark-bar-graph"></i>
                    <span>{{ __('Reports') }}</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Custom Date Range Bar --}}
    <div id="customRangeBar" class="exec-custom-range-bar mb-3" style="{{ $filter === 'custom' ? 'display: block;' : 'display: none;' }}">
        <form method="GET" action="{{ route('home') }}" class="d-flex align-items-center justify-content-between flex-wrap gap-2 w-100 mb-0">
            <input type="hidden" name="filter" value="custom">
            @if($selectedCashierId)
                <input type="hidden" name="cashier_id" value="{{ $selectedCashierId }}">
            @endif
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary px-2 py-2 rounded-3 d-flex align-items-center gap-1">
                    <i class="bi bi-funnel-fill"></i>
                    <strong class="small">{{ __('Date Range Filter') }}:</strong>
                </span>

                <div class="d-flex align-items-center gap-1">
                    <label class="small fw-bold text-muted mb-0 me-1" for="dashStartDate">{{ __('From Date') }}:</label>
                    <input type="date" id="dashStartDate" name="start_date" value="{{ $customStartDate }}" class="exec-date-input" required>
                </div>

                <div class="d-flex align-items-center gap-1">
                    <label class="small fw-bold text-muted mb-0 me-1" for="dashEndDate">{{ __('To Date') }}:</label>
                    <input type="date" id="dashEndDate" name="end_date" value="{{ $customEndDate }}" class="exec-date-input" required>
                </div>

                <button type="submit" class="btn btn-primary btn-sm px-3 fw-bold d-flex align-items-center gap-1 shadow-sm" style="height: 38px; border-radius: 8px;">
                    <i class="bi bi-funnel-fill"></i> {{ __('Filter Statistics') }}
                </button>

                @if($filter === 'custom')
                <a href="{{ route('home', array_filter(['filter' => 'overall', 'cashier_id' => $selectedCashierId])) }}" class="btn btn-outline-secondary btn-sm px-3 d-flex align-items-center gap-1" style="height: 38px; border-radius: 8px;" title="{{ __('Reset Filter') }}">
                    <i class="bi bi-x-circle"></i> {{ __('Reset') }}
                </a>
                @endif
            </div>

            @if($filter === 'custom')
            <div class="small fw-semibold d-flex align-items-center gap-1 py-1 px-2 rounded bg-light border text-primary">
                <i class="bi bi-calendar-check-fill text-primary"></i>
                <span>{{ \Carbon\Carbon::parse($customStartDate)->format('d/m/Y') }} &mdash; {{ \Carbon\Carbon::parse($customEndDate)->format('d/m/Y') }}</span>
            </div>
            @endif
        </form>
    </div>

    {{-- Filter Indicator (When filtering by specific staff) --}}
    @if($selectedCashier)
    @php
        $isOwnerRole = $selectedCashier->id === auth()->id() || $selectedCashier->hasRole('Administrator');
        $initials = strtoupper(substr($selectedCashier->name, 0, 2));
    @endphp
    <div class="filter-banner-strip">
        <div class="d-flex align-items-center gap-2">
            <span class="filter-badge-avatar">{{ $initials }}</span>
            <div>
                <strong class="text-dark">{{ $selectedCashier->name }}</strong>
                <span class="badge {{ $isOwnerRole ? 'bg-dark text-white' : 'bg-primary text-white' }} ms-1" style="font-size: 0.68rem;">
                    {{ $isOwnerRole ? __('Owner') : __('Cashier') }}
                </span>
                <span class="text-muted ms-2" style="font-size: 0.78rem;">
                    &bull; {{ number_format($salesCount) }} {{ __('transactions recorded') }}
                </span>
            </div>
        </div>
        <a href="?cashier_id=all" class="btn btn-sm btn-outline-secondary px-3 py-1 rounded-pill" style="font-size: 0.75rem;">
            <i class="bi bi-x-circle me-1"></i>{{ __('Reset to All Staff') }}
        </a>
    </div>
    @endif

    {{-- ========================================================================
         2. QUICK OPERATIONS BAR (Live Pulse Smooth Infinite Marquee)
         ======================================================================== --}}
    <div class="pulse-marquee-wrapper mb-4">
        <div class="pulse-track">
            @for($loopSet = 0; $loopSet < 2; $loopSet++)
            <div class="quick-op-item">
                <a href="{{ route('sales.create') }}" class="quick-op-btn primary-pos">
                    <div class="quick-op-icon qoi-dark">
                        <i class="bi bi-cart4"></i>
                    </div>
                    <div class="quick-op-texts">
                        <span class="quick-op-label">{{ __('New Sale / POS') }}</span>
                        <span class="quick-op-sub">{{ __('Keyboard (F2)') }}</span>
                    </div>
                </a>
            </div>

            @if(Auth::user()->canManageProducts())
            <div class="quick-op-item">
                <a href="{{ route('products.create') }}" class="quick-op-btn">
                    <div class="quick-op-icon qoi-blue">
                        <i class="bi bi-plus-square"></i>
                    </div>
                    <div class="quick-op-texts">
                        <span class="quick-op-label">{{ __('Add Product') }}</span>
                        <span class="quick-op-sub">{{ __('Catalogue entry') }}</span>
                    </div>
                </a>
            </div>
            @endif

            @if(Auth::user()->hasRole('Administrator') || Auth::user()->hasRole('Super Admin'))
            <div class="quick-op-item">
                <a href="{{ route('purchases.create') }}" class="quick-op-btn">
                    <div class="quick-op-icon qoi-green">
                        <i class="bi bi-box-arrow-in-down"></i>
                    </div>
                    <div class="quick-op-texts">
                        <span class="quick-op-label">{{ __('Stock Intake') }}</span>
                        <span class="quick-op-sub">{{ __('Supplier purchases') }}</span>
                    </div>
                </a>
            </div>

            <div class="quick-op-item">
                <a href="{{ route('expenses.create') }}" class="quick-op-btn">
                    <div class="quick-op-icon qoi-amber">
                        <i class="bi bi-wallet2"></i>
                    </div>
                    <div class="quick-op-texts">
                        <span class="quick-op-label">{{ __('Add Expense') }}</span>
                        <span class="quick-op-sub">{{ __('Bills & operational cost') }}</span>
                    </div>
                </a>
            </div>
            @endif

            <div class="quick-op-item">
                <a href="{{ route('sales.index') }}" class="quick-op-btn">
                    <div class="quick-op-icon qoi-purple">
                        <i class="bi bi-receipt"></i>
                    </div>
                    <div class="quick-op-texts">
                        <span class="quick-op-label">{{ __('Sales History') }}</span>
                        <span class="quick-op-sub">{{ __('Receipts & invoices') }}</span>
                    </div>
                </a>
            </div>
            @endfor
        </div>
    </div>

    {{-- ========================================================================
         3. CORE FINANCIAL METRICS (Static 4-Card Executive Grid - Exactly as Pictured)
         ======================================================================== --}}
    @php
        $avgTicket = $salesCount > 0 ? round($totalSales / $salesCount) : 0;
        $profitMargin = $totalSales > 0 ? round(($grossProfit / $totalSales) * 100, 1) : 0;
        $totalOutflow = $totalPurchases + $totalExpense;
        $returnRate = $totalSales > 0 ? round(($returnsTotal / $totalSales) * 100, 1) : 0;
    @endphp

    <div class="row g-3 mb-4">
        {{-- Card 1: TOTAL SALES (Blue Theme) --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card kpi-card-blue">
                <div class="kpi-card-top">
                    <span class="kpi-card-title">{{ $selectedCashier ? ($isOwnerRole ? __('OWNER SALES') : __('CASHIER SALES')) : __('TOTAL SALES') }}</span>
                    <div class="kpi-card-icon">
                        <i class="bi bi-wallet2"></i>
                    </div>
                </div>
                <div class="kpi-card-value num-tabular">
                    <span>{{ number_format($totalSales) }}</span>
                    <span class="kpi-card-unit">TSh</span>
                </div>
                <div class="kpi-card-footer">
                    <span class="kpi-footer-label">{{ __('Revenue') }}</span>
                    <span class="kpi-badge kpi-badge-blue">{{ __('Inflow') }}</span>
                </div>
            </div>
        </div>

        {{-- Card 2: GROSS PROFIT (Green Theme) --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card kpi-card-green">
                <div class="kpi-card-top">
                    <span class="kpi-card-title">{{ __('GROSS PROFIT') }}</span>
                    <div class="kpi-card-icon">
                        <i class="bi bi-graph-up-arrow"></i>
                    </div>
                </div>
                <div class="kpi-card-value num-tabular">
                    <span>{{ number_format($grossProfit) }}</span>
                    <span class="kpi-card-unit">TSh</span>
                </div>
                <div class="kpi-card-footer">
                    <span class="kpi-footer-label">{{ __('Margin') }}</span>
                    <span class="kpi-badge kpi-badge-green">{{ $profitMargin }}%</span>
                </div>
            </div>
        </div>

        {{-- Card 3: PURCHASES (Amber Theme) --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card kpi-card-amber">
                <div class="kpi-card-top">
                    <span class="kpi-card-title">{{ __('PURCHASES') }}</span>
                    <div class="kpi-card-icon">
                        <i class="bi bi-bag-check"></i>
                    </div>
                </div>
                <div class="kpi-card-value num-tabular">
                    <span>{{ number_format($totalPurchases) }}</span>
                    <span class="kpi-card-unit">TSh</span>
                </div>
                <div class="kpi-card-footer">
                    <span class="kpi-footer-label">{{ __('Stock Cost') }}</span>
                    <span class="kpi-badge kpi-badge-amber">{{ __('Suppliers') }}</span>
                </div>
            </div>
        </div>

        {{-- Card 4: EXPENSES (Rose Theme) --}}
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="kpi-card kpi-card-rose">
                <div class="kpi-card-top">
                    <span class="kpi-card-title">{{ __('EXPENSES') }}</span>
                    <div class="kpi-card-icon">
                        <i class="bi bi-receipt"></i>
                    </div>
                </div>
                <div class="kpi-card-value num-tabular">
                    <span>{{ number_format($totalExpense) }}</span>
                    <span class="kpi-card-unit">TSh</span>
                </div>
                <div class="kpi-card-footer">
                    <span class="kpi-footer-label">{{ __('Operating Cost') }}</span>
                    <span class="kpi-badge kpi-badge-rose">{{ __('Bills & Ops') }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ========================================================================
         NOTE: Picha ya 3 (Secondary Metrics Ribbon: Avg Order Value, Defective Loss, 
         Total Invoices, Active Branch) imeondolewa kabisa kama ulivyoomba.
         ======================================================================== --}}

    {{-- ========================================================================
         4. NET PROFIT MASTER EXECUTIVE STRIP (Admin Only)
         ======================================================================== --}}
    @if($isAdmin && !$selectedCashier)
    @php $isProfit = $netProfit >= 0; @endphp
    <div class="net-profit-hero {{ $isProfit ? 'is-profit' : 'is-loss' }}">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="metric-icon-badge {{ $isProfit ? 'mib-emerald' : 'mib-rose' }}" style="width: 48px; height: 48px; font-size: 1.35rem;">
                    <i class="bi {{ $isProfit ? 'bi-shield-check' : 'bi-exclamation-triangle' }}"></i>
                </div>
                <div>
                    <div class="text-uppercase fw-bold text-muted" style="font-size: 0.72rem; letter-spacing: 0.05em;">
                        {{ $isProfit ? __('Net Operating Profit') : __('Net Operating Loss') }}
                    </div>
                    <div class="num-tabular" style="font-size: 1.85rem; font-weight: 800; color: {{ $isProfit ? '#065f46' : '#991b1b' }}; line-height: 1.1;">
                        {{ $isProfit ? '+' : '' }}{{ number_format($netProfit) }}
                        <span style="font-size: 0.85rem; font-weight: 600; opacity: 0.8;">TSh</span>
                    </div>
                </div>
            </div>

            <div class="d-flex align-items-center gap-3 text-muted" style="font-size: 0.82rem;">
                <div class="d-none d-md-block text-end">
                    <div>{{ __('Gross Profit') }}: <strong class="text-dark">{{ number_format($grossProfit) }} TSh</strong></div>
                    <div>&minus; {{ __('Operating Expenses') }}: <strong class="text-danger">{{ number_format($totalExpense) }} TSh</strong></div>
                </div>
                <div class="border-start ps-3 d-none d-sm-block">
                    <div class="small fw-bold text-muted text-uppercase">{{ __('Net Margin') }}</div>
                    <div class="fw-bold {{ $isProfit ? 'text-success' : 'text-danger' }}" style="font-size: 1.1rem;">
                        {{ $totalSales > 0 ? round(($netProfit / $totalSales) * 100, 1) : 0 }}%
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- ========================================================================
         5. CHARTS & ANALYTICS SECTION
         ======================================================================== --}}
    @if($isAdmin)
    <div class="row g-3 mb-4">
        {{-- Monthly Cash Flow / Revenue Area Chart --}}
        <div class="col-12 {{ !$selectedCashier ? 'col-lg-8' : '' }}">
            <div class="dash-card">
                <div class="dash-card-header">
                    <h4 class="dash-card-title">
                        <i class="bi bi-activity text-primary"></i>
                        <span>
                            @if($selectedCashier)
                                {{ __('Monthly Sales Performance') }}: {{ $selectedCashier->name }}
                            @else
                                {{ __('Annual Cash Flow & Sales Trend') }}
                            @endif
                        </span>
                    </h4>
                    <span class="dash-card-badge">{{ date('Y') }} · {{ __('Monthly Analysis') }}</span>
                </div>
                <div id="cashFlowChart" style="min-height: 220px;"></div>
                
                <div class="chart-summary-bar">
                    <div class="csb-item">
                        <span class="csb-label"><span class="csb-dot" style="background: #10b981;"></span>{{ $selectedCashier ? __('Sales Total') : __('Total Inflow') }}</span>
                        <span class="csb-val num-tabular">{{ number_format(array_sum($monthlyIncome)) }} <small style="font-size: 0.65rem; color:#94a3b8;">TSh</small></span>
                    </div>
                    @if(!$selectedCashier)
                    <div class="csb-item">
                        <span class="csb-label"><span class="csb-dot" style="background: #f59e0b;"></span>{{ __('Total Outflow') }}</span>
                        <span class="csb-val num-tabular">{{ number_format(array_sum($monthlyExpense)) }} <small style="font-size: 0.65rem;">TSh</small></span>
                    </div>
                    <div class="csb-item">
                        @php $ytdNet = array_sum($monthlyNetCash); @endphp
                        <span class="csb-label"><span class="csb-dot" style="background: #2563eb;"></span>{{ __('Net Cash Flow') }}</span>
                        <span class="csb-val num-tabular" style="color: {{ $ytdNet >= 0 ? '#059669' : '#dc2626' }};">
                            {{ $ytdNet >= 0 ? '+' : '' }}{{ number_format($ytdNet) }} <small style="font-size: 0.65rem;">TSh</small>
                        </span>
                    </div>
                    @else
                    <div class="csb-item">
                        <span class="csb-label"><span class="csb-dot" style="background: #2563eb;"></span>{{ __('Recorded Transactions') }}</span>
                        <span class="csb-val num-tabular">{{ number_format($salesCount) }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Expense Distribution Donut Chart --}}
        @if(!$selectedCashier)
        <div class="col-12 col-lg-4">
            <div class="dash-card">
                <div class="dash-card-header">
                    <h4 class="dash-card-title">
                        <i class="bi bi-pie-chart text-warning"></i>
                        <span>{{ __('Expense Outflow') }}</span>
                    </h4>
                    <span class="dash-card-badge">{{ __('Cumulative') }}</span>
                </div>
                <div id="topExpensesChart" style="min-height: 240px;"></div>
            </div>
        </div>
        @endif
    </div>
    @endif

    {{-- ========================================================================
         6. OPERATIONAL TABLES (RECENT SALES & TOP PRODUCTS)
         ======================================================================== --}}
    <div class="row g-3 mb-4">
        {{-- Recent Sales Table --}}
        <div class="col-12 col-lg-8">
            <div class="dash-card">
                <div class="dash-card-header">
                    <h4 class="dash-card-title">
                        <i class="bi bi-clock-history text-primary"></i>
                        <span>
                            @if($selectedCashier)
                                {{ __('Recent Sales by') }} {{ $selectedCashier->name }}
                            @else
                                {{ __('Recent Sales Transactions') }}
                            @endif
                        </span>
                    </h4>
                    <a href="{{ route('sales.index') }}" class="dash-card-link-btn">
                        {{ __('View All Invoices') }} &rarr;
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="exec-table">
                        <thead>
                            <tr>
                                <th>{{ __('Invoice #') }}</th>
                                <th>{{ __('Date & Time') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th class="text-end">{{ __('Amount') }}</th>
                                <th class="text-center">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentSales as $sale)
                            <tr>
                                <td>
                                    <span class="ref-code-badge">{{ $sale->reference_no }}</span>
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark">{{ \Carbon\Carbon::parse($sale->sale_date)->format('d M Y') }}</div>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($sale->created_at)->format('H:i') }}</small>
                                </td>
                                <td>
                                    @if($sale->payment_status == 'paid')
                                        <span class="badge-paid"><i class="bi bi-check-circle-fill"></i> {{ __('Paid') }}</span>
                                    @elseif($sale->payment_status == 'partial')
                                        <span class="badge-partial"><i class="bi bi-clock-fill"></i> {{ __('Partial') }}</span>
                                    @else
                                        <span class="badge-unpaid"><i class="bi bi-x-circle-fill"></i> {{ __('Unpaid') }}</span>
                                    @endif
                                </td>
                                <td class="text-end num-tabular fw-bold text-dark">
                                    {{ number_format($sale->total_amount) }}
                                    <small class="text-muted" style="font-size: 0.68rem; font-weight: 500;">TSh</small>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('sales.show', $sale->id) }}" class="btn btn-sm btn-light border py-1 px-2 rounded-2" title="{{ __('View Details') }}">
                                        <i class="bi bi-eye text-primary"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-2 d-block mb-2 text-secondary"></i>
                                    <span>{{ __('No recent transactions recorded.') }}</span>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Top Products Leaderboard --}}
        <div class="col-12 col-lg-4">
            <div class="dash-card">
                <div class="dash-card-header">
                    <h4 class="dash-card-title">
                        <i class="bi bi-trophy text-warning"></i>
                        <span>{{ __('Top Products') }}</span>
                    </h4>
                    <span class="dash-card-badge">{{ __('By Volume') }}</span>
                </div>

                @if(isset($topProducts) && $topProducts->count())
                    @php 
                        $maxRev = $topProducts->max('total_revenue') ?: 1; 
                    @endphp
                    <div>
                        @foreach($topProducts->take(5) as $idx => $prod)
                        @php
                            $rankClass = $idx === 0 ? 'rank-1' : ($idx === 1 ? 'rank-2' : ($idx === 2 ? 'rank-3' : 'rank-other'));
                            $revPct = round(($prod->total_revenue / $maxRev) * 100);
                        @endphp
                        <div class="top-product-item">
                            <span class="top-rank-badge {{ $rankClass }}">{{ $idx + 1 }}</span>
                            <div class="flex-grow-1" style="min-width: 0;">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <div class="fw-bold text-dark text-truncate me-2" style="font-size: 0.8rem;" title="{{ $prod->name }}">
                                        {{ $prod->name }}
                                    </div>
                                    <span class="num-tabular fw-bold text-dark" style="font-size: 0.8rem; white-space: nowrap;">
                                        {{ number_format($prod->total_revenue) }} <small class="text-muted" style="font-size: 0.65rem;">TSh</small>
                                    </span>
                                </div>
                                <div class="d-flex align-items-center justify-content-between text-muted" style="font-size: 0.7rem;">
                                    <span>{{ number_format($prod->total_qty) }} {{ __('units sold') }}</span>
                                    <span>{{ $revPct }}%</span>
                                </div>
                                <div class="tpi-progress">
                                    <div class="tpi-progress-bar" style="width: {{ $revPct }}%;"></div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5 text-muted my-auto">
                        <i class="bi bi-box-seam fs-2 d-block mb-2 text-secondary"></i>
                        <span>{{ __('No product sales recorded yet for this period.') }}</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

</div>

{{-- ========================================================================
     APEXCHARTS SCRIPT WITH DYNAMIC DARK/LIGHT THEME SUPPORT
     ======================================================================== --}}
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {

    const months = [
        '{{ __('Jan') }}','{{ __('Feb') }}','{{ __('Mar') }}','{{ __('Apr') }}',
        '{{ __('May') }}','{{ __('Jun') }}','{{ __('Jul') }}','{{ __('Aug') }}',
        '{{ __('Sep') }}','{{ __('Oct') }}','{{ __('Nov') }}','{{ __('Dec') }}'
    ];

    const isCurrentDark = document.documentElement.getAttribute('data-theme') === 'dark';

    const baseChartConfig = {
        toolbar: { show: false },
        fontFamily: 'Poppins, -apple-system, sans-serif',
        theme: { mode: isCurrentDark ? 'dark' : 'light' },
        animations: { enabled: true, easing: 'easeinout', speed: 500 }
    };

    let cashFlowChartInstance = null;
    let topExpChartInstance = null;

    @if($isAdmin)
    // --- 1. Monthly Revenue / Cash Flow Area Chart ---
    const cashFlowEl = document.querySelector("#cashFlowChart");
    if (cashFlowEl) {
        cashFlowChartInstance = new ApexCharts(cashFlowEl, {
            series: [{ 
                name: '{{ $selectedCashier ? __("Sales Revenue") : __("Net Cash Flow") }}', 
                data: {!! json_encode($monthlyNetCash) !!} 
            }],
            chart: { ...baseChartConfig, type: 'area', height: 210, background: 'transparent' },
            colors: ['#2563eb'],
            fill: {
                type: 'gradient',
                gradient: { shadeIntensity: 1, opacityFrom: 0.28, opacityTo: 0.02, stops: [0, 95] }
            },
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 2.5 },
            xaxis: {
                categories: months,
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: { style: { fontSize: '11px', colors: isCurrentDark ? '#cbd5e1' : '#64748b' } }
            },
            yaxis: { 
                labels: { 
                    formatter: v => v === 0 ? '0' : (v >= 1000000 ? (v/1000000).toFixed(1)+'M' : (v/1000).toFixed(0)+'K'), 
                    style: { colors: isCurrentDark ? '#cbd5e1' : '#64748b', fontSize: '11px' } 
                } 
            },
            grid: { borderColor: isCurrentDark ? '#1f2937' : '#f1f5f9', strokeDashArray: 3, padding: { left: 0, right: 0 } },
            tooltip: { 
                theme: isCurrentDark ? 'dark' : 'light',
                y: { formatter: val => Number(val).toLocaleString() + " TSh" } 
            }
        });
        cashFlowChartInstance.render();
    }

    @if(!$selectedCashier)
    // --- 2. Top Expenses Donut Chart ---
    const topExpEl = document.querySelector("#topExpensesChart");
    if (topExpEl) {
        const topExpenseData = ({!! json_encode($topExpenseData) !!}).map(Number);
        const topExpenseLabels = {!! json_encode($topExpenseLabels) !!};

        topExpChartInstance = new ApexCharts(topExpEl, {
            series: topExpenseData.length > 0 ? topExpenseData : [1],
            labels: topExpenseLabels.length > 0 ? topExpenseLabels : ['{{ __('No Expenses') }}'],
            chart: { ...baseChartConfig, type: 'donut', height: 260, background: 'transparent' },
            colors: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4'],
            plotOptions: {
                pie: {
                    donut: {
                        size: '72%',
                        labels: {
                            show: true,
                            name: { fontSize: '12px', color: isCurrentDark ? '#cbd5e1' : '#64748b' },
                            value: { 
                                fontSize: '16px', 
                                fontWeight: 800, 
                                color: isCurrentDark ? '#ffffff' : '#0f172a', 
                                formatter: val => Number(val).toLocaleString() + ' TSh' 
                            },
                            total: {
                                show: true,
                                label: '{{ __('Total Outflow') }}',
                                fontSize: '11px',
                                color: isCurrentDark ? '#cbd5e1' : '#64748b',
                                formatter: w => {
                                    if (topExpenseData.length === 0) return '0 TSh';
                                    const sum = w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                    return sum >= 1000000 ? (sum / 1000000).toFixed(1) + 'M TSh' : sum.toLocaleString() + ' TSh';
                                }
                            }
                        }
                    }
                }
            },
            dataLabels: { enabled: false },
            legend: { 
                show: true, 
                position: 'bottom', 
                fontSize: '11px', 
                labels: { colors: isCurrentDark ? '#ffffff' : '#475569' } 
            },
            stroke: { show: false },
            tooltip: { 
                theme: isCurrentDark ? 'dark' : 'light',
                y: { formatter: val => topExpenseData.length > 0 ? Number(val).toLocaleString() + " TSh" : "" } 
            }
        });
        topExpChartInstance.render();
    }
    @endif
    @endif

    // Listen for theme change event from navbar toggle
    window.addEventListener('themeChanged', function(e) {
        const isDark = e.detail.theme === 'dark';
        if (cashFlowChartInstance) {
            cashFlowChartInstance.updateOptions({
                theme: { mode: isDark ? 'dark' : 'light' },
                grid: { borderColor: isDark ? '#1f2937' : '#f1f5f9' },
                tooltip: { theme: isDark ? 'dark' : 'light' },
                xaxis: { labels: { style: { colors: isDark ? '#cbd5e1' : '#64748b' } } },
                yaxis: { labels: { style: { colors: isDark ? '#cbd5e1' : '#64748b' } } }
            });
        }
        if (topExpChartInstance) {
            topExpChartInstance.updateOptions({
                theme: { mode: isDark ? 'dark' : 'light' },
                tooltip: { theme: isDark ? 'dark' : 'light' },
                legend: { labels: { colors: isDark ? '#ffffff' : '#475569' } },
                plotOptions: {
                    pie: {
                        donut: {
                            labels: {
                                name: { color: isDark ? '#cbd5e1' : '#64748b' },
                                value: { color: isDark ? '#ffffff' : '#0f172a' },
                                total: { color: isDark ? '#cbd5e1' : '#64748b' }
                            }
                        }
                    }
                }
            });
        }
    });

    // --- Pulse Marquee: Safe Touch & Hover Movement Engine ---
    (function initPulseMarquee() {
        const wrapper = document.querySelector('.pulse-marquee-wrapper');
        const track = document.querySelector('.pulse-track');
        if (!wrapper || !track) return;

        let resumeTimer = null;

        function pause() {
            clearTimeout(resumeTimer);
            track.classList.add('is-paused');
            track.style.animationPlayState = 'paused';
        }

        function resume(delay = 800) {
            clearTimeout(resumeTimer);
            resumeTimer = setTimeout(() => {
                track.classList.remove('is-paused');
                track.style.animationPlayState = 'running';
            }, delay);
        }

        // Desktop Hover
        wrapper.addEventListener('mouseenter', pause);
        wrapper.addEventListener('mouseleave', () => resume(200));

        // Mobile Touch: Pause safely without hijacking touches or navigation
        wrapper.addEventListener('touchstart', pause, { passive: true });
        wrapper.addEventListener('touchend', () => resume(1200), { passive: true });
        wrapper.addEventListener('touchcancel', () => resume(400), { passive: true });
    })();
});

function handleDashboardPeriodChange(val) {
    if (val === 'custom') {
        toggleCustomRangeBar(true);
    } else {
        const cashierParam = '{{ $selectedCashierId ? "&cashier_id=" . $selectedCashierId : "" }}';
        window.location.href = '?filter=' + val + cashierParam;
    }
}

function toggleCustomRangeBar(forceOpen = false) {
    const bar = document.getElementById('customRangeBar');
    if (!bar) return;
    if (forceOpen) {
        bar.style.display = 'block';
        bar.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    } else {
        bar.style.display = (bar.style.display === 'none' || !bar.style.display) ? 'block' : 'none';
    }
    if (typeof initGlobalDatepickers === 'function') {
        initGlobalDatepickers(bar);
    }
}
</script>
@endsection
