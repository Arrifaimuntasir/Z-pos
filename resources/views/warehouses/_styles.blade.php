<style>
    /* ===== Warehouse Module – Modern Enterprise SaaS Design System ===== */
    .gd {
        --gd-ink: #0f172a;
        --gd-text: #1e293b;
        --gd-muted: #64748b;
        --gd-faint: #94a3b8;
        --gd-line: #e2e8f0;
        --gd-soft: #f8fafc;
        --gd-brand: #2563eb;
        --gd-brand-soft: #eff6ff;
        --gd-green: #16a34a;
        --gd-green-soft: #f0fdf4;
        --gd-amber: #d97706;
        --gd-amber-soft: #fffbeb;
        --gd-red: #dc2626;
        --gd-red-soft: #fef2f2;
        --gd-violet: #7c3aed;
        --gd-violet-soft: #f5f3ff;
    }

    .gd-title {
        font-weight: 800;
        color: var(--gd-ink);
        letter-spacing: -0.5px;
        margin: 0;
        font-size: 1.55rem;
    }
    .gd-sub {
        color: var(--gd-muted);
        font-size: 13.5px;
        margin: 3px 0 0;
        font-weight: 500;
    }

    /* Segmented Navigation Tabs */
    .gd-tabs {
        display: inline-flex;
        gap: 6px;
        padding: 5px;
        background: #ffffff;
        border: 1px solid var(--gd-line);
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.03);
        flex-wrap: wrap;
    }
    .gd-tab {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 18px;
        border-radius: 9px;
        font-size: 13px;
        font-weight: 600;
        color: var(--gd-muted);
        text-decoration: none;
        transition: all 0.18s ease;
    }
    .gd-tab:hover {
        color: var(--gd-ink);
        background: #f1f5f9;
    }
    .gd-tab.active {
        background: #0f172a;
        color: #ffffff;
        box-shadow: 0 2px 6px rgba(15, 23, 42, 0.15);
    }

    /* Buttons */
    .gd-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 18px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 13.5px;
        border: 1px solid transparent;
        text-decoration: none;
        transition: all 0.18s ease;
        cursor: pointer;
        line-height: 1.3;
    }
    .gd-btn-dark {
        background: #0f172a;
        color: #fff;
        box-shadow: 0 2px 6px rgba(15, 23, 42, 0.14);
    }
    .gd-btn-dark:hover {
        background: #1e293b;
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(15, 23, 42, 0.2);
    }
    .gd-btn-ghost {
        background: #ffffff;
        color: var(--gd-text);
        border-color: var(--gd-line);
        box-shadow: 0 1px 2px rgba(0,0,0,0.03);
    }
    .gd-btn-ghost:hover {
        background: #f8fafc;
        color: var(--gd-ink);
        border-color: #cbd5e1;
    }
    .gd-btn-outline {
        background: #ffffff;
        color: var(--gd-brand);
        border-color: #bfdbfe;
    }
    .gd-btn-outline:hover {
        background: #eff6ff;
        border-color: #93c5fd;
        color: #1d4ed8;
    }
    .gd-btn-sm {
        padding: 6px 13px;
        font-size: 12.5px;
        border-radius: 8px;
    }

    /* Cards */
    .gd-card {
        background: #ffffff;
        border: 1px solid var(--gd-line);
        border-radius: 16px;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.03);
    }
    .gd-card-hover {
        transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease;
    }
    .gd-card-hover:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06);
        border-color: #cbd5e1;
    }
    .gd-card-head {
        padding: 18px 22px;
        border-bottom: 1px solid var(--gd-line);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
    }
    .gd-card-head h5 {
        margin: 0;
        font-weight: 700;
        color: var(--gd-ink);
        font-size: 15px;
    }

    /* KPI Summary Cards (Faded / Soft Pastel Tints) */
    .gd-kpi-card {
        border-radius: 14px;
        padding: 18px 20px;
        border: 1px solid var(--gd-line);
        display: flex;
        align-items: center;
        gap: 16px;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.03);
        transition: transform 0.18s ease, box-shadow 0.18s ease;
    }
    .gd-kpi-card:hover {
        transform: translateY(-2px);
    }
    .gd-kpi-blue {
        background: linear-gradient(145deg, #f0f7ff 0%, #e6f1fe 100%);
        border-color: #bfdbfe;
    }
    .gd-kpi-green {
        background: linear-gradient(145deg, #f0fdf4 0%, #e3f9eb 100%);
        border-color: #bbf7d0;
    }
    .gd-kpi-amber {
        background: linear-gradient(145deg, #fffbeb 0%, #fef3c7 100%);
        border-color: #fde68a;
    }
    .gd-kpi-violet {
        background: linear-gradient(145deg, #f5f3ff 0%, #ede9fe 100%);
        border-color: #ddd6fe;
    }

    .gd-kpi-icon {
        width: 44px;
        height: 44px;
        border-radius: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 19px;
        flex-shrink: 0;
        background: #ffffff !important;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    .gd-ic-blue   { color: #2563eb; }
    .gd-ic-green  { color: #16a34a; }
    .gd-ic-amber  { color: #d97706; }
    .gd-ic-violet { color: #7c3aed; }
    .gd-ic-red    { color: #dc2626; }
    .gd-ic-slate  { color: #475569; }

    .gd-kpi-label {
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--gd-muted);
    }
    .gd-kpi-value {
        color: var(--gd-ink);
        font-size: 24px;
        font-weight: 800;
        line-height: 1.15;
        letter-spacing: -0.5px;
        margin-top: 2px;
    }

    /* Badges & Pills */
    .gd-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 3px 10px;
        border-radius: 6px;
        font-size: 11.5px;
        font-weight: 700;
        white-space: nowrap;
        background: #ffffff;
        box-shadow: 0 1px 2px rgba(0,0,0,0.03);
    }
    .gd-pill-green  { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
    .gd-pill-amber  { background: #fffbeb; color: #b45309; border: 1px solid #fde68a; }
    .gd-pill-red    { background: #fff1f2; color: #b91c1c; border: 1px solid #fecdd3; }
    .gd-pill-blue   { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
    .gd-pill-violet { background: #f5f3ff; color: #6d28d9; border: 1px solid #ddd6fe; }
    .gd-pill-teal   { background: #f0fdfa; color: #0f766e; border: 1px solid #99f6e4; }
    .gd-pill-slate  { background: #f8fafc; color: #475569; border: 1px solid #e2e8f0; }
    .gd-dot { width: 6px; height: 6px; border-radius: 50%; background: currentColor; display: inline-block; }

    /* Tables */
    .gd-table {
        width: 100%;
        margin: 0;
        border-collapse: separate;
        border-spacing: 0;
    }
    .gd-table thead th {
        background: #f8fafc;
        color: var(--gd-muted);
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        padding: 13px 18px;
        border-bottom: 1px solid var(--gd-line);
        white-space: nowrap;
    }
    .gd-table tbody td {
        padding: 14px 18px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
        color: var(--gd-text);
        font-size: 13.5px;
    }
    .gd-table tbody tr:last-child td {
        border-bottom: 0;
    }
    .gd-table tbody tr {
        transition: background-color 0.12s ease;
    }
    .gd-table tbody tr:hover {
        background-color: #f8fafc;
    }

    /* Search & Filter inputs */
    .gd-search-wrap {
        position: relative;
        display: flex;
        align-items: center;
    }
    .gd-search-icon {
        position: absolute;
        left: 12px;
        color: var(--gd-muted);
        font-size: 14px;
        pointer-events: none;
    }
    .gd-search-input {
        padding: 8px 14px 8px 34px !important;
        border: 1px solid #cbd5e1 !important;
        border-radius: 9px !important;
        font-size: 13px !important;
        background: #ffffff !important;
        outline: none;
        width: 240px;
        transition: all 0.15s ease;
    }
    .gd-search-input:focus {
        border-color: var(--gd-brand) !important;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12) !important;
        width: 290px;
    }

    /* Forms */
    .gd-label {
        font-size: 13px;
        font-weight: 600;
        color: var(--gd-text);
        margin-bottom: 6px;
        display: block;
    }
    .gd-input, .gd .form-control, .gd .form-select {
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        padding: 9px 13px;
        font-size: 13.5px;
        color: var(--gd-ink);
        background-color: #fff;
        transition: border-color .15s ease, box-shadow .15s ease;
    }
    .gd .form-control:focus, .gd .form-select:focus {
        border-color: var(--gd-brand);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
        outline: none;
    }
    .gd-hint {
        font-size: 12px;
        color: var(--gd-faint);
        margin-top: 5px;
    }

    /* Empty state */
    .gd-empty {
        text-align: center;
        padding: 56px 20px;
    }
    .gd-empty-icon {
        width: 72px;
        height: 72px;
        border-radius: 20px;
        margin: 0 auto 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--gd-brand-soft);
        color: var(--gd-brand);
        font-size: 30px;
    }

    /* Alerts */
    .gd-alert {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 18px;
        border-radius: 12px;
        font-size: 13.5px;
        font-weight: 500;
        margin-bottom: 18px;
        border: 1px solid;
    }
    .gd-alert-success {
        background: #f0fdf4;
        border-color: #bbf7d0;
        color: #166534;
    }
    .gd-alert-error {
        background: #fff1f2;
        border-color: #fecdd3;
        color: #9f1239;
    }

    /* Round icon buttons */
    .gd-iconbtn {
        width: 34px;
        height: 34px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 9px;
        border: 1px solid var(--gd-line);
        background: #fff;
        color: var(--gd-muted);
        transition: all 0.15s ease;
        cursor: pointer;
        text-decoration: none;
        font-size: 14px;
    }
    .gd-iconbtn:hover {
        background: #f1f5f9;
        color: var(--gd-ink);
        border-color: #cbd5e1;
    }
    .gd-iconbtn.danger:hover {
        background: var(--gd-red-soft);
        color: var(--gd-red);
        border-color: #fecaca;
    }

    @media (max-width: 575.98px) {
        .gd-tab { padding: 7px 12px; font-size: 12.5px; }
        .gd-kpi-card { padding: 14px 16px; }
        .gd-search-input { width: 100%; }
        .gd-search-input:focus { width: 100%; }
    }

    /* ===== Warehouse Module – Enterprise Dark Mode Overrides ===== */
    [data-theme="dark"] .gd,
    body.dark-mode .gd,
    html.dark-mode .gd {
        --gd-ink: #f8fafc;
        --gd-text: #e2e8f0;
        --gd-muted: #94a3b8;
        --gd-faint: #64748b;
        --gd-line: #1f2937;
        --gd-soft: #111827;
        --gd-brand-soft: rgba(37, 99, 235, 0.2);
        --gd-green-soft: rgba(22, 163, 74, 0.2);
        --gd-amber-soft: rgba(217, 119, 6, 0.2);
        --gd-red-soft: rgba(220, 38, 38, 0.2);
        --gd-violet-soft: rgba(124, 58, 237, 0.2);
    }

    [data-theme="dark"] .gd-tabs,
    body.dark-mode .gd-tabs {
        background: #111827 !important;
        border-color: #1f2937 !important;
    }
    [data-theme="dark"] .gd-tab,
    body.dark-mode .gd-tab {
        color: #94a3b8 !important;
    }
    [data-theme="dark"] .gd-tab:hover,
    body.dark-mode .gd-tab:hover {
        color: #ffffff !important;
        background: #1e293b !important;
    }
    [data-theme="dark"] .gd-tab.active,
    body.dark-mode .gd-tab.active {
        background: #2563eb !important;
        color: #ffffff !important;
        box-shadow: 0 2px 8px rgba(37, 99, 235, 0.3) !important;
    }

    [data-theme="dark"] .gd-card,
    body.dark-mode .gd-card {
        background: #111827 !important;
        border-color: #1f2937 !important;
        color: #f1f5f9 !important;
    }
    [data-theme="dark"] .gd-card-head,
    body.dark-mode .gd-card-head {
        border-bottom-color: #1f2937 !important;
    }
    [data-theme="dark"] .gd-card-head h5,
    body.dark-mode .gd-card-head h5 {
        color: #ffffff !important;
    }

    [data-theme="dark"] .gd-btn-ghost,
    body.dark-mode .gd-btn-ghost {
        background: #1e293b !important;
        color: #ffffff !important;
        border-color: #334155 !important;
    }
    [data-theme="dark"] .gd-btn-ghost:hover,
    body.dark-mode .gd-btn-ghost:hover {
        background: #334155 !important;
        color: #ffffff !important;
    }
    [data-theme="dark"] .gd-btn-outline,
    body.dark-mode .gd-btn-outline {
        background: transparent !important;
        color: #60a5fa !important;
        border-color: #3b82f6 !important;
    }

    [data-theme="dark"] .gd-kpi-card,
    body.dark-mode .gd-kpi-card {
        background: #111827 !important;
        border-color: #1f2937 !important;
    }
    [data-theme="dark"] .gd-kpi-blue, body.dark-mode .gd-kpi-blue {
        background: linear-gradient(145deg, #111827 0%, rgba(37,99,235,0.2) 100%) !important;
        border-color: #1e3a8a !important;
    }
    [data-theme="dark"] .gd-kpi-green, body.dark-mode .gd-kpi-green {
        background: linear-gradient(145deg, #111827 0%, rgba(22,163,74,0.2) 100%) !important;
        border-color: #14532d !important;
    }
    [data-theme="dark"] .gd-kpi-amber, body.dark-mode .gd-kpi-amber {
        background: linear-gradient(145deg, #111827 0%, rgba(217,119,6,0.2) 100%) !important;
        border-color: #78350f !important;
    }
    [data-theme="dark"] .gd-kpi-violet, body.dark-mode .gd-kpi-violet {
        background: linear-gradient(145deg, #111827 0%, rgba(124,58,237,0.2) 100%) !important;
        border-color: #4c1d95 !important;
    }
    [data-theme="dark"] .gd-kpi-icon,
    body.dark-mode .gd-kpi-icon {
        background: #1e293b !important;
        box-shadow: 0 2px 6px rgba(0,0,0,0.3) !important;
    }

    [data-theme="dark"] .gd-table thead th,
    body.dark-mode .gd-table thead th {
        background: #0f172a !important;
        color: #cbd5e1 !important;
        border-bottom-color: #1f2937 !important;
    }
    [data-theme="dark"] .gd-table tbody td,
    body.dark-mode .gd-table tbody td {
        border-bottom-color: #1f2937 !important;
        color: #f1f5f9 !important;
        background: transparent !important;
    }
    [data-theme="dark"] .gd-table tbody tr:hover,
    body.dark-mode .gd-table tbody tr:hover {
        background-color: #1e293b !important;
    }

    [data-theme="dark"] .gd-search-input,
    body.dark-mode .gd-search-input {
        background: #111827 !important;
        border-color: #374151 !important;
        color: #ffffff !important;
    }
    [data-theme="dark"] .gd-search-input::placeholder,
    body.dark-mode .gd-search-input::placeholder {
        color: #94a3b8 !important;
    }

    [data-theme="dark"] .gd-input,
    [data-theme="dark"] .gd .form-control,
    [data-theme="dark"] .gd .form-select,
    body.dark-mode .gd-input,
    body.dark-mode .gd .form-control,
    body.dark-mode .gd .form-select {
        background-color: #111827 !important;
        border-color: #374151 !important;
        color: #ffffff !important;
    }

    [data-theme="dark"] .gd-iconbtn,
    body.dark-mode .gd-iconbtn {
        background: #1e293b !important;
        border-color: #334155 !important;
        color: #cbd5e1 !important;
    }
    [data-theme="dark"] .gd-iconbtn:hover,
    body.dark-mode .gd-iconbtn:hover {
        background: #334155 !important;
        color: #ffffff !important;
    }

    [data-theme="dark"] .gd-pill,
    body.dark-mode .gd-pill {
        background: #1e293b !important;
        border-color: #334155 !important;
        color: #cbd5e1 !important;
    }
    [data-theme="dark"] .gd-pill-green, body.dark-mode .gd-pill-green { background: rgba(22, 163, 74, 0.2) !important; color: #4ade80 !important; border-color: rgba(22, 163, 74, 0.4) !important; }
    [data-theme="dark"] .gd-pill-amber, body.dark-mode .gd-pill-amber { background: rgba(217, 119, 6, 0.2) !important; color: #fbbf24 !important; border-color: rgba(217, 119, 6, 0.4) !important; }
    [data-theme="dark"] .gd-pill-red, body.dark-mode .gd-pill-red { background: rgba(220, 38, 38, 0.2) !important; color: #f87171 !important; border-color: rgba(220, 38, 38, 0.4) !important; }
    [data-theme="dark"] .gd-pill-blue, body.dark-mode .gd-pill-blue { background: rgba(37, 99, 235, 0.2) !important; color: #60a5fa !important; border-color: rgba(37, 99, 235, 0.4) !important; }
    [data-theme="dark"] .gd-pill-violet, body.dark-mode .gd-pill-violet { background: rgba(124, 58, 237, 0.2) !important; color: #a78bfa !important; border-color: rgba(124, 58, 237, 0.4) !important; }
    [data-theme="dark"] .gd-pill-teal, body.dark-mode .gd-pill-teal { background: rgba(13, 148, 136, 0.2) !important; color: #2dd4bf !important; border-color: rgba(13, 148, 136, 0.4) !important; }
    [data-theme="dark"] .gd-pill-slate, body.dark-mode .gd-pill-slate { background: rgba(148, 163, 184, 0.2) !important; color: #cbd5e1 !important; border-color: rgba(148, 163, 184, 0.4) !important; }

    /* Buttons, Stat Boxes, Filter Cards & Route Elements in Dark Mode */
    .gd-stat-box { background: #f8fafc; border: 1px solid #f1f5f9; }
    .gd-stat-num { color: var(--gd-ink); }
    .gd-card-foot { border-top: 1px solid var(--gd-line); background: #fafafa; }

    [data-theme="dark"] .gd-stat-box,
    body.dark-mode .gd-stat-box {
        background: #1e293b !important;
        border-color: #334155 !important;
    }
    [data-theme="dark"] .gd-stat-num,
    body.dark-mode .gd-stat-num {
        color: #ffffff !important;
    }
    [data-theme="dark"] .gd-card-foot,
    body.dark-mode .gd-card-foot {
        border-top-color: #1f2937 !important;
        background: #0f172a !important;
    }

    [data-theme="dark"] .gd-btn-dark,
    body.dark-mode .gd-btn-dark {
        background: #2563eb !important;
        border-color: #3b82f6 !important;
        color: #ffffff !important;
        box-shadow: 0 2px 8px rgba(37, 99, 235, 0.35) !important;
    }
    [data-theme="dark"] .gd-btn-dark:hover,
    body.dark-mode .gd-btn-dark:hover {
        background: #1d4ed8 !important;
        border-color: #2563eb !important;
        color: #ffffff !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.5) !important;
    }

    [data-theme="dark"] .filter-card,
    body.dark-mode .filter-card {
        background: #111827 !important;
        border-color: #1f2937 !important;
        color: #f1f5f9 !important;
    }

    [data-theme="dark"] .gd-endpoint,
    body.dark-mode .gd-endpoint {
        background: #111827 !important;
        border-color: #1f2937 !important;
        color: #f1f5f9 !important;
    }
    [data-theme="dark"] .gd-endpoint.from,
    body.dark-mode .gd-endpoint.from {
        border-color: rgba(220, 38, 38, 0.4) !important;
        background: linear-gradient(180deg, rgba(220, 38, 38, 0.15) 0%, #111827 48px) !important;
    }
    [data-theme="dark"] .gd-endpoint.to,
    body.dark-mode .gd-endpoint.to {
        border-color: rgba(22, 163, 74, 0.4) !important;
        background: linear-gradient(180deg, rgba(22, 163, 74, 0.15) 0%, #111827 48px) !important;
    }
    [data-theme="dark"] .gd-seg,
    body.dark-mode .gd-seg {
        background: #1e293b !important;
        border: 1px solid #334155 !important;
    }
    [data-theme="dark"] .gd-seg label,
    body.dark-mode .gd-seg label {
        color: #94a3b8 !important;
    }
    [data-theme="dark"] .gd-seg input:checked + label,
    body.dark-mode .gd-seg input:checked + label {
        background: #2563eb !important;
        color: #ffffff !important;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.3) !important;
    }
    [data-theme="dark"] .gd-swap,
    body.dark-mode .gd-swap {
        background: #1e293b !important;
        border-color: #334155 !important;
        color: #cbd5e1 !important;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.3) !important;
    }
    [data-theme="dark"] .gd-swap:hover,
    body.dark-mode .gd-swap:hover {
        background: #2563eb !important;
        border-color: #3b82f6 !important;
        color: #ffffff !important;
    }
    [data-theme="dark"] .transfer-table th,
    body.dark-mode .transfer-table th {
        background: #0f172a !important;
        color: #cbd5e1 !important;
        border-bottom-color: #1f2937 !important;
    }
    [data-theme="dark"] .transfer-table td,
    body.dark-mode .transfer-table td {
        border-bottom-color: #1f2937 !important;
        color: #f1f5f9 !important;
    }
    [data-theme="dark"] .btn-add-item,
    body.dark-mode .btn-add-item {
        background: #1e293b !important;
        border-color: #334155 !important;
        color: #cbd5e1 !important;
    }
    [data-theme="dark"] .btn-add-item:hover,
    body.dark-mode .btn-add-item:hover {
        background: #334155 !important;
        color: #ffffff !important;
        border-color: #475569 !important;
    }
    [data-theme="dark"] .route-summary-box,
    body.dark-mode .route-summary-box {
        background: #1e293b !important;
        border-color: #334155 !important;
        color: #f1f5f9 !important;
    }
    [data-theme="dark"] .gd-empty,
    body.dark-mode .gd-empty {
        background: #111827 !important;
        border-color: #1f2937 !important;
        color: #f1f5f9 !important;
    }
    [data-theme="dark"] .gd-empty-icon,
    body.dark-mode .gd-empty-icon {
        background: #1e293b !important;
        color: #94a3b8 !important;
    }
    [data-theme="dark"] .progress,
    body.dark-mode .progress {
        background-color: #1e293b !important;
    }
</style>
