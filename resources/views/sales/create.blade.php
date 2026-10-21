@extends('layouts.admin')
@section('title', 'Point of Sale (POS)')
@section('content')
@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
.select2-container .select2-selection--single{height:38px;border:1px solid #dee2e6;border-radius:8px;background:#f8f9fa;}
.select2-container--default .select2-selection--single .select2-selection__rendered{line-height:38px;color:#212529;}
.select2-container--default .select2-selection--single .select2-selection__arrow{height:36px;}

/* Enterprise Supermarket POS Architecture */
:root {
  --spos-primary: #2563eb;
  --spos-primary-dark: #1d4ed8;
  --spos-primary-light: #eff6ff;
  --spos-success: #059669;
  --spos-success-light: #ecfdf5;
  --spos-amber: #d97706;
  --spos-amber-light: #fffbeb;
  --spos-danger: #dc2626;
  --spos-danger-light: #fef2f2;
  --spos-dark: #0f172a;
  --spos-border: #e2e8f0;
  --spos-card-bg: #ffffff;
  --spos-panel-bg: #f8fafc;
}

.spos-container {
  display: flex;
  flex-direction: column;
  height: calc(100vh - 120px);
  min-height: 640px;
  background: var(--spos-card-bg);
  border: 1px solid var(--spos-border);
  border-radius: 16px;
  overflow: hidden;
  box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.06);
}

/* Top Sub-Header Bar */
.spos-topbar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 10px 18px;
  background: #ffffff;
  border-bottom: 1px solid var(--spos-border);
  flex-shrink: 0;
  gap: 12px;
}
.spos-title-area {
  display: flex;
  align-items: center;
  gap: 10px;
}
.spos-badge-brand {
  background: linear-gradient(135deg, #2563eb, #1d4ed8);
  color: #fff;
  font-size: 0.75rem;
  font-weight: 700;
  letter-spacing: 0.03em;
  padding: 4px 10px;
  border-radius: 20px;
  display: inline-flex;
  align-items: center;
  gap: 5px;
}
.spos-top-time {
  font-size: 0.78rem;
  color: #64748b;
  font-weight: 500;
}

/* Mobile View Switcher (Desktop hidden, Mobile visible) */
.spos-mobile-tabs {
  display: none;
  background: #f1f5f9;
  padding: 3px;
  border-radius: 10px;
  gap: 4px;
}
.spos-mtab-btn {
  border: none;
  background: transparent;
  padding: 6px 14px;
  border-radius: 8px;
  font-size: 0.8rem;
  font-weight: 700;
  color: #64748b;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 6px;
  transition: all 0.2s ease;
}
.spos-mtab-btn.active {
  background: #ffffff;
  color: var(--spos-primary);
  box-shadow: 0 2px 6px rgba(0,0,0,0.06);
}
.spos-mtab-badge {
  background: var(--spos-primary);
  color: #fff;
  font-size: 0.68rem;
  padding: 1px 6px;
  border-radius: 10px;
  font-weight: 700;
}

/* Quick Tools in Topbar */
.spos-top-actions {
  display: flex;
  align-items: center;
  gap: 8px;
}
.spos-tool-btn {
  background: #f8fafc;
  border: 1px solid var(--spos-border);
  color: #475569;
  font-size: 0.78rem;
  font-weight: 600;
  padding: 6px 12px;
  border-radius: 8px;
  display: inline-flex;
  align-items: center;
  gap: 6px;
  cursor: pointer;
  text-decoration: none;
  transition: all 0.15s ease;
}
.spos-tool-btn:hover {
  background: #f1f5f9;
  color: #1e293b;
  border-color: #cbd5e1;
}
.spos-tool-btn.active-held {
  background: var(--spos-amber-light);
  border-color: #fde68a;
  color: var(--spos-amber);
}

/* Main Split POS Body */
.spos-main-body {
  display: flex;
  flex: 1;
  min-height: 0;
  overflow: hidden;
}

/* Left Column: Products & Search */
.spos-catalog-col {
  flex: 1;
  display: flex;
  flex-direction: column;
  background: #f8fafc;
  border-right: 1px solid var(--spos-border);
  min-width: 0;
  min-height: 0;
}

/* Search Bar Area */
.spos-search-bar {
  background: #ffffff;
  padding: 12px 16px;
  border-bottom: 1px solid var(--spos-border);
  display: flex;
  align-items: center;
  gap: 12px;
  flex-shrink: 0;
}
.spos-input-wrap {
  flex: 1;
  position: relative;
  display: flex;
  align-items: center;
}
.spos-input-icon {
  position: absolute;
  left: 14px;
  font-size: 1.15rem;
  color: var(--spos-primary);
  pointer-events: none;
}
.spos-search-input {
  width: 100%;
  background: #f8fafc;
  border: 1.5px solid #cbd5e1;
  border-radius: 10px;
  color: #0f172a;
  font-size: 0.95rem;
  font-weight: 600;
  padding: 10px 38px 10px 42px;
  outline: none;
  transition: all 0.2s ease;
}
.spos-search-input::placeholder {
  color: #94a3b8;
  font-weight: 400;
}
.spos-search-input:focus {
  background: #ffffff;
  border-color: var(--spos-primary);
  box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
}
.spos-input-clear {
  position: absolute;
  right: 12px;
  border: none;
  background: transparent;
  color: #94a3b8;
  font-size: 0.9rem;
  cursor: pointer;
  display: none;
}
.spos-input-clear:hover {
  color: #ef4444;
}
.spos-shortcuts-tag {
  display: flex;
  align-items: center;
  gap: 5px;
  flex-shrink: 0;
}
.spos-shortcut-pill {
  background: #f1f5f9;
  border: 1px solid #e2e8f0;
  color: #475569;
  font-size: 0.68rem;
  font-weight: 700;
  padding: 3px 7px;
  border-radius: 6px;
}

/* Category Scroller Bar */
.spos-category-bar {
  background: #ffffff;
  border-bottom: 1px solid var(--spos-border);
  padding: 8px 16px;
  display: flex;
  gap: 8px;
  overflow-x: auto;
  flex-shrink: 0;
  scrollbar-width: thin;
}
.spos-category-bar::-webkit-scrollbar {
  height: 4px;
}
.spos-category-bar::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 4px;
}
.spos-cat-chip {
  border: 1px solid #e2e8f0;
  background: #f8fafc;
  color: #475569;
  font-size: 0.78rem;
  font-weight: 600;
  padding: 6px 14px;
  border-radius: 20px;
  white-space: nowrap;
  cursor: pointer;
  transition: all 0.15s ease;
  display: inline-flex;
  align-items: center;
  gap: 6px;
}
.spos-cat-chip:hover {
  background: #eff6ff;
  border-color: #bfdbfe;
  color: var(--spos-primary);
}
.spos-cat-chip.active {
  background: linear-gradient(135deg, #2563eb, #1d4ed8);
  border-color: #2563eb;
  color: #ffffff;
  box-shadow: 0 2px 8px rgba(37, 99, 235, 0.25);
}
.spos-cat-count {
  background: rgba(0,0,0,0.06);
  padding: 1px 6px;
  border-radius: 10px;
  font-size: 0.68rem;
}
.spos-cat-chip.active .spos-cat-count {
  background: rgba(255,255,255,0.25);
  color: #fff;
}

/* Product Cards Grid */
.spos-products-grid {
  flex: 1;
  overflow-y: auto;
  padding: 14px;
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(138px, 1fr));
  gap: 12px;
  align-content: start;
}
.spos-products-grid::-webkit-scrollbar {
  width: 6px;
}
.spos-products-grid::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 4px;
}

/* Product Card */
.spos-pcard {
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 12px;
  padding: 10px 10px 8px 10px;
  cursor: pointer;
  transition: all 0.15s ease;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  text-align: left;
  position: relative;
  overflow: hidden;
  min-height: 125px;
}
.spos-pcard:hover {
  border-color: var(--spos-primary);
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(37, 99, 235, 0.1);
}
.spos-pcard:active {
  transform: scale(0.97);
  background: #eff6ff;
}
.spos-pcard.out-of-stock {
  opacity: 0.55;
  cursor: not-allowed;
  filter: grayscale(0.5);
  background: #f8fafc;
}
.spos-pcard-top {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 4px;
  margin-bottom: 6px;
}
.spos-pcard-header {
  display: flex;
  align-items: center;
  gap: 8px;
  margin-bottom: 6px;
}
.spos-pcard-thumb {
  width: 44px;
  height: 44px;
  border-radius: 8px;
  overflow: hidden;
  background: #f1f5f9;
  flex-shrink: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  border: 1px solid #e2e8f0;
}
.spos-pcard-thumb img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
.spos-pcard-fallback {
  color: #94a3b8;
  font-size: 1.15rem;
}
.spos-pcard-badges {
  flex: 1;
  min-width: 0;
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  gap: 3px;
}
.spos-pcat-badge {
  font-size: 0.62rem;
  color: #64748b;
  font-weight: 600;
  text-transform: uppercase;
  max-width: 65px;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}
.spos-stock-pill {
  font-size: 0.65rem;
  font-weight: 700;
  padding: 2px 6px;
  border-radius: 6px;
  white-space: nowrap;
}
.spos-stock-pill.in {
  background: var(--spos-success-light);
  color: var(--spos-success);
}
.spos-stock-pill.low {
  background: var(--spos-amber-light);
  color: var(--spos-amber);
}
.spos-stock-pill.out {
  background: var(--spos-danger-light);
  color: var(--spos-danger);
}

/* Active Scanned Product Showcase Banner */
.spos-scanned-banner {
  background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%);
  border: 1.5px solid #86efac;
  border-radius: 12px;
  padding: 10px 12px;
  margin: 8px 14px 4px 14px;
  display: flex;
  align-items: center;
  gap: 12px;
  position: relative;
  box-shadow: 0 4px 12px rgba(16, 185, 129, 0.08);
  animation: sposSlideDown 0.25s cubic-bezier(0.16, 1, 0.3, 1);
  flex-shrink: 0;
}
@keyframes sposSlideDown {
  from { opacity: 0; transform: translateY(-8px); }
  to { opacity: 1; transform: translateY(0); }
}
.spos-sb-thumb-box {
  width: 52px;
  height: 52px;
  border-radius: 10px;
  overflow: hidden;
  background: #ffffff;
  border: 1.5px solid #a7f3d0;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  box-shadow: 0 2px 6px rgba(0,0,0,0.06);
}
.spos-sb-thumb-box img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
.spos-sb-info {
  flex: 1;
  min-width: 0;
}
.spos-sb-status {
  display: flex;
  align-items: center;
  gap: 6px;
  font-size: 0.68rem;
  font-weight: 700;
  color: #059669;
  text-transform: uppercase;
  letter-spacing: 0.04em;
  margin-bottom: 2px;
}
.spos-sb-pulse {
  width: 7px;
  height: 7px;
  border-radius: 50%;
  background: #10b981;
  box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
  animation: sposPulse 1.6s infinite;
}
@keyframes sposPulse {
  0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
  70% { box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
  100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
}
.spos-sb-name {
  font-size: 0.88rem;
  font-weight: 800;
  color: #0f172a;
  line-height: 1.25;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
.spos-sb-meta {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-top: 3px;
}
.spos-sb-code {
  font-size: 0.72rem;
  color: #475569;
  font-family: monospace;
  background: #ffffff;
  padding: 1px 6px;
  border-radius: 4px;
  border: 1px solid #cbd5e1;
}
.spos-sb-price {
  font-size: 0.92rem;
  font-weight: 800;
  color: #059669;
}
.spos-sb-close {
  border: none;
  background: transparent;
  color: #94a3b8;
  font-size: 1.1rem;
  cursor: pointer;
  padding: 0 4px;
  border-radius: 4px;
  align-self: flex-start;
}
.spos-sb-close:hover {
  color: #ef4444;
}

.spos-pcard-name {
  font-size: 0.82rem;
  font-weight: 600;
  color: #0f172a;
  line-height: 1.35;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
  word-break: break-word;
  margin-bottom: 4px;
}
.spos-pcard-barcode {
  font-size: 0.65rem;
  color: #94a3b8;
  font-family: monospace;
  margin-bottom: 6px;
}
.spos-pcard-expiry {
  font-size: 0.62rem;
  font-weight: 600;
  border-radius: 4px;
  padding: 1px 4px;
  display: inline-block;
  margin-bottom: 4px;
}
.spos-pcard-expiry.valid {
  background: #f1f5f9;
  color: #475569;
}
.spos-pcard-expiry.expired {
  background: var(--spos-danger-light);
  color: var(--spos-danger);
}
.spos-pcard-price {
  font-size: 0.88rem;
  font-weight: 800;
  color: var(--spos-success);
  margin-top: auto;
}

/* Empty Catalog State */
.spos-empty-catalog {
  grid-column: 1 / -1;
  text-align: center;
  padding: 50px 20px;
  color: #94a3b8;
}

/* Right Column: Cart & Checkout */
.spos-cart-col {
  width: 400px;
  display: flex;
  flex-direction: column;
  background: #ffffff;
  flex-shrink: 0;
  min-height: 0;
}

/* Cart Header */
.spos-cart-header {
  padding: 12px 16px;
  border-bottom: 1px solid var(--spos-border);
  display: flex;
  flex-direction: column;
  gap: 10px;
  flex-shrink: 0;
}
.spos-cart-hdr-row {
  display: flex;
  align-items: center;
  justify-content: space-between;
}
.spos-cart-title {
  font-weight: 700;
  color: #0f172a;
  font-size: 0.95rem;
  display: flex;
  align-items: center;
  gap: 8px;
}
.spos-cart-badge {
  background: var(--spos-primary);
  color: #ffffff;
  border-radius: 20px;
  font-size: 0.72rem;
  font-weight: 700;
  padding: 2px 8px;
}
.spos-clear-btn {
  border: none;
  background: transparent;
  color: #94a3b8;
  font-size: 0.78rem;
  font-weight: 600;
  cursor: pointer;
  padding: 2px 6px;
  border-radius: 6px;
  transition: all 0.15s;
}
.spos-clear-btn:hover {
  background: #fef2f2;
  color: #dc2626;
}

/* Customer Selector */
.spos-cust-wrap {
  display: flex;
  align-items: center;
  gap: 8px;
}
.spos-cust-icon {
  color: #64748b;
  font-size: 1.1rem;
}
.spos-cust-select {
  flex: 1;
  background: #f8fafc;
  border: 1px solid #cbd5e1;
  border-radius: 8px;
  font-size: 0.82rem;
  font-weight: 600;
  color: #1e293b;
  padding: 6px 10px;
  outline: none;
}
.spos-cust-select:focus {
  border-color: var(--spos-primary);
}

/* Cart Items Body */
.spos-cart-list {
  flex: 1;
  overflow-y: auto;
  padding: 10px 14px;
}
.spos-cart-list::-webkit-scrollbar {
  width: 5px;
}
.spos-cart-list::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 4px;
}

.spos-empty-cart {
  text-align: center;
  padding: 40px 20px;
  color: #94a3b8;
}
.spos-empty-icon {
  font-size: 2.8rem;
  color: #cbd5e1;
  display: block;
  margin-bottom: 10px;
}

/* Cart Item Card */
.spos-cart-row {
  background: #ffffff;
  border: 1px solid #f1f5f9;
  border-radius: 10px;
  padding: 8px 10px;
  margin-bottom: 8px;
  display: flex;
  gap: 10px;
  align-items: center;
  transition: all 0.15s ease;
}
.spos-cart-row:hover {
  border-color: #cbd5e1;
  box-shadow: 0 2px 6px rgba(0,0,0,0.03);
}
.spos-crow-thumb {
  width: 38px;
  height: 38px;
  border-radius: 8px;
  overflow: hidden;
  background: #f8fafc;
  border: 1px solid #e2e8f0;
  flex-shrink: 0;
  display: flex;
  align-items: center;
  justify-content: center;
}
.spos-crow-thumb img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
.spos-crow-thumb.fallback {
  color: #94a3b8;
  font-size: 1.05rem;
}
.spos-crow-info {
  flex: 1;
  min-width: 0;
}
.spos-crow-name {
  font-size: 0.82rem;
  font-weight: 600;
  color: #0f172a;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}
.spos-crow-meta {
  display: flex;
  align-items: center;
  gap: 8px;
  margin-top: 2px;
}
.spos-crow-price {
  font-size: 0.75rem;
  color: #64748b;
  font-weight: 500;
}
.spos-crow-disc {
  font-size: 0.65rem;
  background: #fef9c3;
  color: #854d0e;
  padding: 1px 5px;
  border-radius: 4px;
  font-weight: 700;
}

/* Quantity Stepper */
.spos-stepper {
  display: flex;
  align-items: center;
  gap: 3px;
  margin-top: 5px;
}
.spos-step-btn {
  width: 26px;
  height: 26px;
  border-radius: 6px;
  border: 1px solid #e2e8f0;
  background: #f8fafc;
  font-weight: 700;
  font-size: 0.95rem;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #1e293b;
  transition: all 0.1s;
}
.spos-step-btn:hover {
  background: var(--spos-primary);
  color: #ffffff;
  border-color: var(--spos-primary);
}
.spos-step-input {
  width: 38px;
  height: 26px;
  border: 1px solid #e2e8f0;
  background: #ffffff;
  border-radius: 6px;
  font-size: 0.82rem;
  font-weight: 700;
  text-align: center;
  color: #0f172a;
  outline: none;
  padding: 0;
}
.spos-disc-trigger {
  border: none;
  background: #fef9c3;
  color: #854d0e;
  font-size: 0.68rem;
  font-weight: 700;
  padding: 4px 6px;
  border-radius: 6px;
  cursor: pointer;
  margin-left: 4px;
}
.spos-disc-trigger:hover {
  background: #fef08a;
}

/* Row Right Side */
.spos-crow-right {
  display: flex;
  flex-direction: column;
  align-items: flex-end;
  justify-content: space-between;
  gap: 6px;
  flex-shrink: 0;
}
.spos-crow-remove {
  border: none;
  background: transparent;
  color: #94a3b8;
  font-size: 0.9rem;
  cursor: pointer;
  padding: 0;
  transition: color 0.15s;
}
.spos-crow-remove:hover {
  color: #ef4444;
}
.spos-crow-total {
  font-size: 0.88rem;
  font-weight: 700;
  color: #0f172a;
}

/* Cart Footer: Summary & Payment */
.spos-cart-footer {
  border-top: 1px solid var(--spos-border);
  padding: 12px 16px;
  background: #ffffff;
  flex-shrink: 0;
  display: flex;
  flex-direction: column;
  gap: 8px;
}
.spos-summary-line {
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 0.8rem;
  color: #64748b;
  font-weight: 500;
}
.spos-summary-line.discount {
  color: #ef4444;
}

/* Grand Total Banner */
.spos-grand-banner {
  background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
  border-radius: 12px;
  padding: 10px 14px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  color: #ffffff;
  margin: 2px 0;
}
.spos-gb-label {
  font-size: 0.82rem;
  font-weight: 600;
  color: #94a3b8;
  letter-spacing: 0.04em;
}
.spos-gb-amount {
  font-size: 1.35rem;
  font-weight: 800;
  color: #38bdf8;
}

/* Payment Method Pills */
.spos-pay-methods-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 5px;
}
.spos-pay-chip {
  border: 1.5px solid #e2e8f0;
  background: #f8fafc;
  border-radius: 8px;
  padding: 6px 4px;
  cursor: pointer;
  text-align: center;
  font-size: 0.72rem;
  font-weight: 700;
  color: #475569;
  transition: all 0.15s ease;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 2px;
}
.spos-pay-chip i {
  font-size: 1.05rem;
}
.spos-pay-chip.active {
  border-color: var(--spos-primary);
  background: var(--spos-primary-light);
  color: var(--spos-primary-dark);
}

/* Sub-Payment Panels (Mobile Money & Bank) */
.spos-sub-payment-panel {
  background: #f8fafc;
  border: 1px solid #e2e8f0;
  border-radius: 10px;
  padding: 8px 10px;
  margin-top: 4px;
  display: flex;
  flex-direction: column;
  gap: 6px;
  animation: sposFadeIn 0.15s ease-in-out;
}
@keyframes sposFadeIn {
  from { opacity: 0; transform: translateY(-3px); }
  to { opacity: 1; transform: translateY(0); }
}
.spos-sub-title {
  font-size: 0.68rem;
  font-weight: 700;
  color: #475569;
  text-transform: uppercase;
  letter-spacing: 0.03em;
}
.spos-provider-chips {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 6px;
}
.spos-provider-chip {
  border: 1.5px solid #e2e8f0;
  background: #ffffff;
  border-radius: 8px;
  padding: 6px 10px;
  font-size: 0.75rem;
  font-weight: 700;
  color: #1e293b;
  cursor: pointer;
  transition: all 0.15s ease;
  display: flex;
  align-items: center;
  gap: 8px;
  box-shadow: 0 1px 2px rgba(0,0,0,0.02);
}
.spos-provider-chip:hover {
  border-color: #cbd5e1;
  background: #f8fafc;
  transform: translateY(-1px);
}
.spos-provider-chip.active {
  border-color: var(--spos-primary);
  background: #eff6ff;
  color: var(--spos-primary-dark) !important;
  box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.2);
}
.spos-brand-logo-badge {
  width: 26px;
  height: 26px;
  border-radius: 6px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.65rem;
  font-weight: 900;
  color: #ffffff;
  flex-shrink: 0;
  box-shadow: 0 1px 3px rgba(0,0,0,0.15);
}
.badge-mpesa { background: #e60000; }
.badge-yas { background: linear-gradient(135deg, #7928ca 0%, #ff0080 100%); }
.badge-airtel { background: #ed1c24; }
.badge-halotel { background: #ff6600; }
.badge-crdb { background: #008751; font-size: 0.58rem; }
.badge-nmb { background: #002b7f; border-bottom: 2px solid #ff7a00; font-size: 0.58rem; }
.badge-nbc { background: #003399; font-size: 0.58rem; }
.badge-stanbic { background: #0033aa; font-size: 0.75rem; }
.badge-kcb { background: #00853f; font-size: 0.58rem; }
.badge-absa { background: #b00020; font-size: 0.55rem; text-transform: lowercase; }
.badge-equity { background: #6b2c26; font-size: 0.75rem; }
.badge-dtb { background: #c8102e; font-size: 0.58rem; }
.badge-otherbank { background: #475569; font-size: 0.75rem; }

/* Modern High-End Numpad Grid */
.spos-numpad-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 4px;
  margin-top: 4px;
}
.spos-np-btn {
  border: 1px solid #e2e8f0;
  background: #ffffff;
  border-radius: 8px;
  padding: 8px 0;
  font-size: 1.05rem;
  font-weight: 700;
  color: #1e293b;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.1s ease;
  user-select: none;
  box-shadow: 0 1px 2px rgba(0,0,0,0.03);
}
.spos-np-btn:hover {
  background: #f1f5f9;
  border-color: #cbd5e1;
  transform: translateY(-1px);
}
.spos-np-btn:active {
  background: #e2e8f0;
  transform: scale(0.96);
}
.spos-np-btn.clear-key {
  background: #fef2f2;
  color: #dc2626;
  border-color: #fecaca;
}
.spos-np-btn.clear-key:hover {
  background: #fee2e2;
  border-color: #f87171;
}

/* Amount Paid & Live Change */
.spos-tender-box {
  display: flex;
  gap: 8px;
  align-items: center;
  background: #f8fafc;
  border: 1px solid #e2e8f0;
  border-radius: 8px;
  padding: 6px 10px;
}
.spos-tender-input-wrap {
  flex: 1;
}
.spos-tender-label {
  font-size: 0.65rem;
  font-weight: 700;
  color: #64748b;
  text-transform: uppercase;
  margin-bottom: 2px;
}
.spos-tender-input {
  width: 100%;
  border: none;
  background: transparent;
  font-size: 1rem;
  font-weight: 800;
  color: #0f172a;
  outline: none;
  padding: 0;
}
.spos-change-display {
  text-align: right;
  flex-shrink: 0;
}
.spos-change-label {
  font-size: 0.65rem;
  font-weight: 700;
  color: #64748b;
  text-transform: uppercase;
}
.spos-change-val {
  font-size: 0.95rem;
  font-weight: 800;
  color: var(--spos-success);
}
.spos-change-val.due {
  color: var(--spos-danger);
}

/* Scrollable Area Inside Cart Column */
.spos-cart-scroll-area {
  flex: 1;
  overflow-y: auto;
  min-height: 0;
  display: flex;
  flex-direction: column;
}
.spos-cart-scroll-area::-webkit-scrollbar {
  width: 5px;
}
.spos-cart-scroll-area::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 4px;
}

/* Pinned Checkout Bottom Bar */
.spos-pinned-checkout-bar {
  background: #ffffff;
  border-top: 2px solid #e2e8f0;
  box-shadow: 0 -6px 20px rgba(15, 23, 42, 0.08);
  padding: 12px 16px;
  flex-shrink: 0;
  z-index: 20;
  display: flex;
  flex-direction: column;
  gap: 8px;
}
.spos-btn-complete-massive {
  width: 100%;
  height: 52px;
  background: linear-gradient(135deg, #059669 0%, #10b981 100%);
  border: none;
  border-radius: 12px;
  color: #ffffff;
  font-size: 1.05rem;
  font-weight: 800;
  letter-spacing: 0.02em;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0 18px;
  box-shadow: 0 6px 20px rgba(16, 185, 129, 0.35);
  transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
}
.spos-btn-complete-massive:hover {
  background: linear-gradient(135deg, #047857 0%, #059669 100%);
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(16, 185, 129, 0.45);
}
.spos-btn-complete-massive:active {
  transform: scale(0.98);
}
.spos-comp-btn-left {
  display: flex;
  align-items: center;
  gap: 10px;
  font-size: 1.05rem;
}
.spos-comp-total-pill {
  background: rgba(255, 255, 255, 0.22);
  border: 1px solid rgba(255, 255, 255, 0.4);
  backdrop-filter: blur(4px);
  color: #ffffff;
  font-size: 0.95rem;
  font-weight: 800;
  padding: 4px 12px;
  border-radius: 20px;
}
.spos-pinned-sub-actions {
  display: flex;
  gap: 8px;
}
.spos-btn-pinned-hold {
  flex: 1;
  background: #fffbeb;
  border: 1.5px solid #fde68a;
  color: #b45309;
  font-size: 0.78rem;
  font-weight: 700;
  padding: 7px 10px;
  border-radius: 8px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 6px;
  transition: all 0.15s;
}
.spos-btn-pinned-hold:hover {
  background: #fef3c7;
  border-color: #f59e0b;
}
.spos-btn-pinned-proforma {
  flex: 1;
  background: #f8fafc;
  border: 1.5px dashed #cbd5e1;
  color: #475569;
  font-size: 0.78rem;
  font-weight: 700;
  padding: 7px 10px;
  border-radius: 8px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 6px;
  transition: all 0.15s;
}
.spos-btn-pinned-proforma:hover {
  background: #f1f5f9;
  color: #1e293b;
  border-color: #94a3b8;
}

/* Main Action Buttons Legacy Fallbacks */
.spos-actions-group {
  display: flex;
  gap: 6px;
  margin-top: 4px;
}
.spos-btn-hold {
  background: var(--spos-amber-light);
  border: 1.5px solid #fde68a;
  color: #b45309;
  font-size: 0.8rem;
  font-weight: 700;
  padding: 10px 12px;
  border-radius: 10px;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  gap: 5px;
  transition: all 0.15s;
}
.spos-btn-hold:hover {
  background: #fef3c7;
  border-color: #f59e0b;
}
.spos-btn-complete {
  flex: 1;
  background: linear-gradient(135deg, #059669, #10b981);
  border: none;
  color: #ffffff;
  font-size: 0.9rem;
  font-weight: 800;
  padding: 10px 16px;
  border-radius: 10px;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  box-shadow: 0 4px 12px rgba(16, 185, 129, 0.28);
  transition: all 0.15s ease;
}
.spos-proforma-btn {
  border: 1px dashed #cbd5e1;
  background: #f8fafc;
  color: #475569;
  font-size: 0.75rem;
  font-weight: 600;
  padding: 6px 10px;
  border-radius: 8px;
  cursor: pointer;
  text-align: center;
  transition: all 0.15s;
}
.spos-proforma-btn:hover {
  background: #f1f5f9;
  color: #1e293b;
  border-color: #94a3b8;
}

/* Floating Mobile Bottom Checkout Bar */
.spos-mobile-bottom-bar {
  display: none;
  position: sticky;
  bottom: 0;
  left: 0;
  right: 0;
  z-index: 90;
  background: #0f172a;
  color: #ffffff;
  padding: 12px 18px;
  box-shadow: 0 -4px 20px rgba(0,0,0,0.18);
  align-items: center;
  justify-content: space-between;
  border-radius: 16px 16px 0 0;
}
.spos-mbb-info {
  display: flex;
  flex-direction: column;
}
.spos-mbb-count {
  font-size: 0.75rem;
  color: #94a3b8;
  font-weight: 600;
}
.spos-mbb-total {
  font-size: 1.15rem;
  font-weight: 800;
  color: #38bdf8;
}
.spos-mbb-btn {
  background: linear-gradient(135deg, #2563eb, #1d4ed8);
  border: none;
  color: #ffffff;
  font-size: 0.88rem;
  font-weight: 700;
  padding: 8px 18px;
  border-radius: 10px;
  display: inline-flex;
  align-items: center;
  gap: 6px;
  box-shadow: 0 4px 12px rgba(37,99,235,0.3);
}

/* Responsive Breakpoints for Mobile & Tablet */
@media (max-width: 991px) {
  .spos-container {
    height: auto;
    min-height: calc(100vh - 120px);
    border-radius: 12px;
  }
  .spos-topbar {
    flex-wrap: wrap;
    padding: 10px 14px;
  }
  .spos-mobile-tabs {
    display: flex;
    order: 3;
    width: 100%;
    justify-content: center;
    margin-top: 6px;
  }
  .spos-main-body {
    flex-direction: column;
    height: auto;
  }
  
  /* View Switcher Visibility */
  .spos-container[data-mobile-view="catalog"] .spos-catalog-col {
    display: flex;
    height: auto;
    min-height: 500px;
  }
  .spos-container[data-mobile-view="catalog"] .spos-cart-col {
    display: none;
  }
  .spos-container[data-mobile-view="catalog"] .spos-mobile-bottom-bar.has-items {
    display: flex;
  }

  .spos-container[data-mobile-view="cart"] .spos-catalog-col {
    display: none;
  }
  .spos-container[data-mobile-view="cart"] .spos-cart-col {
    display: flex;
    width: 100%;
    min-height: calc(100vh - 200px);
  }
  .spos-container[data-mobile-view="cart"] .spos-mobile-bottom-bar {
    display: none;
  }

  .spos-products-grid {
    grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
    padding: 10px;
    gap: 8px;
  }
  .spos-shortcuts-tag {
    display: none;
  }
}

/* =============================================
   SUPERMARKET POS ENTERPRISE DARK MODE
============================================= */
[data-theme="dark"] .spos-container,
body.dark-mode .spos-container {
  background: #0f172a !important;
  border-color: #1e293b !important;
  box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.4) !important;
}

[data-theme="dark"] .spos-topbar,
body.dark-mode .spos-topbar {
  background: #111827 !important;
  border-bottom-color: #1f2937 !important;
}
[data-theme="dark"] .spos-top-time,
body.dark-mode .spos-top-time {
  color: #94a3b8 !important;
}
[data-theme="dark"] .spos-tool-btn,
body.dark-mode .spos-tool-btn {
  background: #1e293b !important;
  border-color: #334155 !important;
  color: #e2e8f0 !important;
}
[data-theme="dark"] .spos-tool-btn:hover,
body.dark-mode .spos-tool-btn:hover {
  background: #334155 !important;
  color: #ffffff !important;
  border-color: #475569 !important;
}
[data-theme="dark"] .spos-mobile-tabs,
body.dark-mode .spos-mobile-tabs {
  background: #1e293b !important;
}
[data-theme="dark"] .spos-mtab-btn,
body.dark-mode .spos-mtab-btn {
  color: #94a3b8 !important;
}
[data-theme="dark"] .spos-mtab-btn.active,
body.dark-mode .spos-mtab-btn.active {
  background: #0f172a !important;
  color: #38bdf8 !important;
  box-shadow: 0 2px 6px rgba(0,0,0,0.3) !important;
}

[data-theme="dark"] .spos-catalog-col,
body.dark-mode .spos-catalog-col {
  background: #0b1120 !important;
  border-right-color: #1f2937 !important;
}
[data-theme="dark"] .spos-search-bar,
body.dark-mode .spos-search-bar {
  background: #111827 !important;
  border-bottom-color: #1f2937 !important;
}
[data-theme="dark"] .spos-search-input,
body.dark-mode .spos-search-input {
  background: #1e293b !important;
  border-color: #374151 !important;
  color: #ffffff !important;
}
[data-theme="dark"] .spos-search-input::placeholder,
body.dark-mode .spos-search-input::placeholder {
  color: #94a3b8 !important;
}
[data-theme="dark"] .spos-search-input:focus,
body.dark-mode .spos-search-input:focus {
  background: #1e293b !important;
  border-color: #3b82f6 !important;
}
[data-theme="dark"] .spos-shortcut-pill,
body.dark-mode .spos-shortcut-pill {
  background: #1e293b !important;
  border-color: #334155 !important;
  color: #cbd5e1 !important;
}

[data-theme="dark"] .spos-category-bar,
body.dark-mode .spos-category-bar {
  background: #111827 !important;
  border-bottom-color: #1f2937 !important;
}
[data-theme="dark"] .spos-cat-chip,
body.dark-mode .spos-cat-chip {
  background: #1e293b !important;
  border-color: #334155 !important;
  color: #cbd5e1 !important;
}
[data-theme="dark"] .spos-cat-chip:hover,
body.dark-mode .spos-cat-chip:hover {
  background: #2563eb !important;
  color: #ffffff !important;
  border-color: #2563eb !important;
}
[data-theme="dark"] .spos-cat-chip.active,
body.dark-mode .spos-cat-chip.active {
  background: linear-gradient(135deg, #2563eb, #1d4ed8) !important;
  border-color: #2563eb !important;
  color: #ffffff !important;
}

[data-theme="dark"] .spos-pcard,
body.dark-mode .spos-pcard {
  background: #111827 !important;
  border-color: #1f2937 !important;
  color: #f1f5f9 !important;
}
[data-theme="dark"] .spos-pcard:hover,
body.dark-mode .spos-pcard:hover {
  border-color: #3b82f6 !important;
  box-shadow: 0 6px 16px rgba(0, 0, 0, 0.4) !important;
  background: #1a2333 !important;
}
[data-theme="dark"] .spos-pcard-name,
body.dark-mode .spos-pcard-name {
  color: #f1f5f9 !important;
}
[data-theme="dark"] .spos-pcard-thumb,
body.dark-mode .spos-pcard-thumb {
  background: #1e293b !important;
  border-color: #334155 !important;
}
[data-theme="dark"] .spos-pcat-badge,
body.dark-mode .spos-pcat-badge {
  color: #94a3b8 !important;
}
[data-theme="dark"] .spos-pcard-expiry.valid,
body.dark-mode .spos-pcard-expiry.valid {
  background: #1e293b !important;
  color: #94a3b8 !important;
}

[data-theme="dark"] .spos-scanned-banner,
body.dark-mode .spos-scanned-banner {
  background: linear-gradient(135deg, #064e3b 0%, #065f46 100%) !important;
  border-color: #059669 !important;
}
[data-theme="dark"] .spos-sb-thumb-box,
body.dark-mode .spos-sb-thumb-box {
  background: #111827 !important;
  border-color: #059669 !important;
}
[data-theme="dark"] .spos-sb-name,
body.dark-mode .spos-sb-name {
  color: #ffffff !important;
}
[data-theme="dark"] .spos-sb-code,
body.dark-mode .spos-sb-code {
  background: #111827 !important;
  border-color: #1f2937 !important;
  color: #cbd5e1 !important;
}

[data-theme="dark"] .spos-cart-col,
body.dark-mode .spos-cart-col {
  background: #111827 !important;
}
[data-theme="dark"] .spos-cart-header,
body.dark-mode .spos-cart-header {
  border-bottom-color: #1f2937 !important;
}
[data-theme="dark"] .spos-cart-title,
body.dark-mode .spos-cart-title {
  color: #ffffff !important;
}
[data-theme="dark"] .spos-cust-select,
body.dark-mode .spos-cust-select {
  background: #1e293b !important;
  border-color: #374151 !important;
  color: #ffffff !important;
}
[data-theme="dark"] .spos-cust-select option,
body.dark-mode .spos-cust-select option {
  background-color: #111827 !important;
  color: #ffffff !important;
}

[data-theme="dark"] .spos-cart-row,
body.dark-mode .spos-cart-row {
  background: #1e293b !important;
  border-color: #334155 !important;
}
[data-theme="dark"] .spos-crow-name,
body.dark-mode .spos-crow-name {
  color: #ffffff !important;
}
[data-theme="dark"] .spos-crow-price,
body.dark-mode .spos-crow-price {
  color: #94a3b8 !important;
}
[data-theme="dark"] .spos-crow-thumb,
body.dark-mode .spos-crow-thumb {
  background: #0f172a !important;
  border-color: #334155 !important;
}
[data-theme="dark"] .spos-step-btn,
body.dark-mode .spos-step-btn {
  background: #0f172a !important;
  border-color: #334155 !important;
  color: #ffffff !important;
}
[data-theme="dark"] .spos-step-btn:hover,
body.dark-mode .spos-step-btn:hover {
  background: #2563eb !important;
  color: #ffffff !important;
}
[data-theme="dark"] .spos-step-input,
body.dark-mode .spos-step-input {
  background: #0f172a !important;
  border-color: #334155 !important;
  color: #ffffff !important;
}
[data-theme="dark"] .spos-crow-total,
body.dark-mode .spos-crow-total {
  color: #38bdf8 !important;
}

[data-theme="dark"] .spos-cart-footer,
body.dark-mode .spos-cart-footer {
  background: #111827 !important;
  border-top-color: #1f2937 !important;
}
[data-theme="dark"] .spos-summary-line,
body.dark-mode .spos-summary-line {
  color: #94a3b8 !important;
}
[data-theme="dark"] .spos-pinned-checkout-bar,
body.dark-mode .spos-pinned-checkout-bar {
  background: #111827 !important;
  border-top-color: #1f2937 !important;
  box-shadow: 0 -6px 20px rgba(0, 0, 0, 0.4) !important;
}

[data-theme="dark"] .spos-pay-chip,
body.dark-mode .spos-pay-chip {
  background: #1e293b !important;
  border-color: #334155 !important;
  color: #cbd5e1 !important;
}
[data-theme="dark"] .spos-pay-chip.active,
body.dark-mode .spos-pay-chip.active {
  background: rgba(37, 99, 235, 0.25) !important;
  border-color: #3b82f6 !important;
  color: #93c5fd !important;
}
[data-theme="dark"] .spos-sub-payment-panel,
body.dark-mode .spos-sub-payment-panel {
  background: #1e293b !important;
  border-color: #334155 !important;
}
[data-theme="dark"] .spos-provider-chip,
body.dark-mode .spos-provider-chip {
  background: #0f172a !important;
  border-color: #334155 !important;
  color: #ffffff !important;
}
[data-theme="dark"] .spos-provider-chip:hover,
body.dark-mode .spos-provider-chip:hover {
  border-color: #475569 !important;
  background: #1e293b !important;
}
[data-theme="dark"] .spos-provider-chip.active,
body.dark-mode .spos-provider-chip.active {
  border-color: #3b82f6 !important;
  background: rgba(37, 99, 235, 0.25) !important;
  color: #93c5fd !important;
}

[data-theme="dark"] .spos-np-btn,
body.dark-mode .spos-np-btn {
  background: #1e293b !important;
  border-color: #334155 !important;
  color: #ffffff !important;
}
[data-theme="dark"] .spos-np-btn:hover,
body.dark-mode .spos-np-btn:hover {
  background: #334155 !important;
}
[data-theme="dark"] .spos-tender-box,
body.dark-mode .spos-tender-box {
  background: #1e293b !important;
  border-color: #334155 !important;
}
[data-theme="dark"] .spos-tender-input,
body.dark-mode .spos-tender-input {
  color: #ffffff !important;
}
[data-theme="dark"] .spos-btn-pinned-proforma,
body.dark-mode .spos-btn-pinned-proforma {
  background: #1e293b !important;
  border-color: #334155 !important;
  color: #cbd5e1 !important;
}
[data-theme="dark"] .spos-proforma-btn,
body.dark-mode .spos-proforma-btn {
  background: #1e293b !important;
  border-color: #334155 !important;
  color: #cbd5e1 !important;
}

/* Modals inside POS */
[data-theme="dark"] #paymentModal .modal-content,
[data-theme="dark"] #discountModal .modal-content,
[data-theme="dark"] #holdModal .modal-content,
[data-theme="dark"] #customerModal .modal-content,
body.dark-mode #paymentModal .modal-content,
body.dark-mode #discountModal .modal-content,
body.dark-mode #holdModal .modal-content,
body.dark-mode #customerModal .modal-content {
  background-color: #111827 !important;
  border-color: #1f2937 !important;
  color: #ffffff !important;
}
</style>
@endpush

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show mb-3"><i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif
@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show mb-3"><ul class="mb-0 mt-1">@foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif

@php
    $shopCategory = Auth::user()->shop->business_type ?? '';
    $isSupermarket = $shopCategory === 'Supermarket / Grocery';
    $showExpiry = in_array($shopCategory, ['Pharmacy / Health','Supermarket / Grocery','Restaurant / Food']);
    $showImei = in_array($shopCategory, ['Electronics / IT','Hardware / Construction']);
    $isRestaurant = $shopCategory === 'Restaurant / Food';
    $groupedProducts = $products->groupBy(function($p){ return $p->category ? $p->category->name : 'Other'; });
@endphp

@if($isSupermarket)
{{-- SUPERMARKET POS --}}
<form action="{{ route('sales.store') }}" method="POST" id="posForm">
    @csrf
    <input type="hidden" name="sale_date" id="hiddenDate" value="{{ date('Y-m-d') }}">
    <input type="hidden" name="customer_id" id="hiddenCustomer" value="">
    <input type="hidden" name="paid_amount" id="hiddenPaid" value="0">
    <input type="hidden" name="payment_method" id="hiddenPayment" value="Cash">
    <input type="hidden" name="notes" id="hiddenNotes" value="">
    <input type="hidden" name="action" id="hiddenAction" value="sale">
    <div id="cartHiddenInputs"></div>
</form>

<div class="spos-container" id="sposContainer" data-mobile-view="catalog">
  {{-- TOP SUB-HEADER BAR --}}
  <div class="spos-topbar">
    <div class="spos-title-area">
      <span class="spos-badge-brand">
        <i class="bi bi-shop-window"></i> {{ __('Supermarket POS') }}
      </span>
      <span class="spos-top-time d-none d-md-inline">
        <i class="bi bi-calendar3 me-1"></i>{{ date('D, d M Y') }}
      </span>
    </div>

    {{-- Mobile View Switcher --}}
    <div class="spos-mobile-tabs" id="mobileTabs">
      <button type="button" class="spos-mtab-btn active" id="mtabCatalogBtn" onclick="switchMobileView('catalog')">
        <i class="bi bi-grid-fill"></i> {{ __('Catalog') }}
      </button>
      <button type="button" class="spos-mtab-btn" id="mtabCartBtn" onclick="switchMobileView('cart')">
        <i class="bi bi-cart3"></i> {{ __('Cart') }}
        <span class="spos-mtab-badge" id="mtabCartBadge">0</span>
      </button>
    </div>

    {{-- Top Action Utilities --}}
    <div class="spos-top-actions">
      <button type="button" class="spos-tool-btn" id="heldSalesBtn" onclick="openHeldSalesModal()" title="{{ __('Held Sales') }}">
        <i class="bi bi-pause-circle"></i>
        <span class="d-none d-sm-inline">{{ __('Held Sales') }}</span>
        <span class="badge bg-warning text-dark rounded-pill ms-1" id="heldCountBadge" style="display:none;">0</span>
      </button>

      <button type="button" class="spos-tool-btn" id="soundToggleBtn" onclick="toggleSound()" title="{{ __('Sound On') }}">
        <i class="bi bi-volume-up" id="soundIcon"></i>
        <span class="d-none d-md-inline" id="soundText">{{ __('Sound On') }}</span>
      </button>

      <a href="{{ route('sales.index') }}" class="spos-tool-btn" title="{{ __('Sales History') }}">
        <i class="bi bi-clock-history"></i>
        <span class="d-none d-sm-inline">{{ __('Sales History') }}</span>
      </a>
    </div>
  </div>

  {{-- MAIN WORKSPACE (CATALOG + CART) --}}
  <div class="spos-main-body">
    {{-- LEFT COLUMN: PRODUCT CATALOG & SCANNER --}}
    <div class="spos-catalog-col" id="catalogCol">
      {{-- Scan & Search Bar --}}
      <div class="spos-search-bar">
        <div class="spos-input-wrap">
          <i class="bi bi-upc-scan spos-input-icon"></i>
          <input type="text" id="barcodeInput" class="spos-search-input" placeholder="{{ __('Scan barcode or type product name...') }}" autocomplete="off" autofocus>
          <button type="button" class="spos-input-clear" id="barcodeClearBtn" onclick="clearSearchInput()"><i class="bi bi-x-circle-fill"></i></button>
        </div>
      </div>

      {{-- Category Scroll Pills --}}
      <div class="spos-category-bar" id="categoryTabs">
        <button type="button" class="spos-cat-chip active" data-cat="ALL">
          <i class="bi bi-stars"></i> {{ __('All') }}
          <span class="spos-cat-count">{{ $products->count() }}</span>
        </button>
        @foreach($groupedProducts as $catName => $catProducts)
          <button type="button" class="spos-cat-chip" data-cat="{{ $catName }}">
            {{ $catName }}
            <span class="spos-cat-count">{{ $catProducts->count() }}</span>
          </button>
        @endforeach
      </div>

      {{-- Product Grid --}}
      <div class="spos-products-grid" id="productGrid">
        @foreach($products as $p)
        @php
          $stockNum   = $p->track_stock ? $p->current_stock : 999999;
          $isOut      = $p->track_stock && $stockNum <= 0;
          $isLow      = $p->track_stock && $stockNum > 0 && $stockNum <= 5;
          $stockClass = $p->track_stock ? ($isOut ? 'out' : ($isLow ? 'low' : 'in')) : '';
          $stockLabel = $p->track_stock ? ($isOut ? __('Out of Stock') : ($isLow ? $stockNum : $stockNum)) : '';
          $expiryStr  = ($showExpiry && $p->expiry_date) ? \Carbon\Carbon::parse($p->expiry_date)->format('d/m/Y') : '';
          $isExpP     = ($showExpiry && $p->expiry_date) ? \Carbon\Carbon::parse($p->expiry_date)->isPast() : false;
        @endphp
        <div class="spos-pcard {{ $isOut ? 'out-of-stock' : '' }}"
             data-cat="{{ $p->category ? $p->category->name : 'Other' }}"
             data-id="{{ $p->id }}"
             data-name="{{ $p->name }}"
             data-barcode="{{ $p->barcode ?? $p->sku ?? '' }}"
             @php
               $cleanCardPrice = (abs($p->selling_price - round($p->selling_price)) < 0.1) ? (int)round($p->selling_price) : $p->selling_price;
             @endphp
             data-price="{{ $cleanCardPrice }}"
             data-stock="{{ $stockNum }}"
             data-image="{{ $p->image_path ? asset($p->image_path) : '' }}"
             data-expiry="{{ $expiryStr }}"
             data-expired="{{ $isExpP ? '1' : '0' }}"
             data-disabled="{{ $isOut ? '1' : '0' }}"
             onclick="handleProductClick(this)">
          <div class="spos-pcard-header">
            <div class="spos-pcard-thumb">
              @if($p->image_path)
                <img src="{{ asset($p->image_path) }}" alt="{{ $p->name }}" loading="lazy">
              @else
                <div class="spos-pcard-fallback"><i class="bi bi-box-seam"></i></div>
              @endif
            </div>
            <div class="spos-pcard-badges">
              <span class="spos-pcat-badge">{{ $p->category ? $p->category->name : 'General' }}</span>
              @if($p->track_stock)
                <span class="spos-stock-pill {{ $stockClass }}">
                  @if($isOut)
                    {{ __('Out of Stock') }}
                  @else
                    {{ $stockLabel }}
                  @endif
                </span>
              @endif
            </div>
          </div>
          <div class="spos-pcard-name" title="{{ $p->name }}">{{ $p->name }}</div>
          @if($p->barcode ?? $p->sku ?? null)
            <div class="spos-pcard-barcode"><i class="bi bi-upc me-1"></i>{{ $p->barcode ?? $p->sku }}</div>
          @endif
          @if($expiryStr)
            <div class="spos-pcard-expiry {{ $isExpP ? 'expired' : 'valid' }}">
              <i class="bi bi-calendar-event me-1"></i>{{ $isExpP ? __('Expired') : __('Expires') }}: {{ $expiryStr }}
            </div>
          @endif
          <div class="spos-pcard-price">{{ number_format($p->selling_price) }} TSh</div>
        </div>
        @endforeach

        <div class="spos-empty-catalog" id="emptyCatalogMsg" style="display:none;">
          <i class="bi bi-search fs-1 mb-2 d-block text-muted"></i>
          <div class="fw-bold fs-6 text-dark">{{ __('No products found matching your search') }}</div>
        </div>
      </div>

      {{-- Floating Mobile Bottom Checkout Bar --}}
      <div class="spos-mobile-bottom-bar" id="mobileBottomBar" onclick="switchMobileView('cart')">
        <div class="spos-mbb-info">
          <span class="spos-mbb-count" id="mbbCount">0 {{ __('Items') }}</span>
          <span class="spos-mbb-total" id="mbbTotal">0 TSh</span>
        </div>
        <button type="button" class="spos-mbb-btn">
          <span>{{ __('View Cart & Checkout') }}</span>
          <i class="bi bi-arrow-right"></i>
        </button>
      </div>
    </div>

    {{-- RIGHT COLUMN: CART & CHECKOUT PANEL --}}
    <div class="spos-cart-col" id="cartCol">
      {{-- Cart Header --}}
      <div class="spos-cart-header">
        <div class="spos-cart-hdr-row">
          <div class="spos-cart-title">
            <i class="bi bi-cart3 text-primary"></i>
            <span>{{ __('Cart') }}</span>
            <span class="spos-cart-badge" id="cartCountBadge">0</span>
          </div>
          <div class="d-flex align-items-center gap-2">
            <button type="button" class="btn btn-sm btn-outline-secondary d-md-none" onclick="switchMobileView('catalog')">
              <i class="bi bi-arrow-left me-1"></i>{{ __('Catalog') }}
            </button>
            <button type="button" class="spos-clear-btn" onclick="clearCart()" title="{{ __('Clear Cart') }}">
              <i class="bi bi-trash3 me-1"></i>{{ __('Clear Cart') }}
            </button>
          </div>
        </div>
        <div class="spos-cust-wrap">
          <i class="bi bi-person spos-cust-icon"></i>
          <select id="customerSelect" class="spos-cust-select" onchange="document.getElementById('hiddenCustomer').value=this.value">
            <option value="">{{ __('Walk-in Customer') }}</option>
            @foreach($customers as $cu)
              <option value="{{ $cu->id }}">{{ $cu->name }}</option>
            @endforeach
          </select>
        </div>
      </div>

      {{-- Active Scanned Product Showcase Banner --}}
      <div class="spos-scanned-banner" id="scannedProductBanner" style="display:none;">
        <div class="spos-sb-thumb-box" id="sbThumbBox">
          <i class="bi bi-upc-scan fs-4 text-success" id="sbFallbackIcon"></i>
          <img src="" id="sbProductImg" alt="Product" style="display:none;">
        </div>
        <div class="spos-sb-info">
          <div class="spos-sb-status">
            <span class="spos-sb-pulse"></span>
            <span id="sbStatusText">{{ __('Item Added to Cart') }}</span>
          </div>
          <div class="spos-sb-name" id="sbProductName">-</div>
          <div class="spos-sb-meta">
            <span class="spos-sb-code" id="sbProductCode"><i class="bi bi-upc me-1"></i>-</span>
            <span class="spos-sb-price" id="sbProductPrice">0 TSh</span>
          </div>
        </div>
        <button type="button" class="spos-sb-close" onclick="closeScannedBanner()" title="Close"><i class="bi bi-x"></i></button>
      </div>

      {{-- Scrollable Cart & Payment Zone --}}
      <div class="spos-cart-scroll-area">
        {{-- Cart Items Scrollable List --}}
        <div class="spos-cart-list" id="cartBody">
          <div class="spos-empty-cart" id="emptyMsg">
            <i class="bi bi-cart-x spos-empty-icon"></i>
            <div class="fw-bold text-dark mb-1" style="font-size:0.9rem;">{{ __('Cart is empty') }}</div>
            <div class="small text-muted">{{ __('Scan or click a product') }}</div>
          </div>
        </div>

        {{-- Cart Details & Controls --}}
        <div class="spos-cart-footer">
          {{-- Summary Breakdown --}}
          <div class="spos-summary-line">
            <span>{{ __('Subtotal') }}</span>
            <strong id="dispSubtotal">0 TSh</strong>
          </div>
          <div class="spos-summary-line discount" id="dispDiscountRow" style="display:none;">
            <span>{{ __('Discount') }}</span>
            <strong id="dispDiscount">-0 TSh</strong>
          </div>

          {{-- Grand Total High-Contrast Banner --}}
          <div class="spos-grand-banner">
            <span class="spos-gb-label">{{ __('Grand Total') }}</span>
            <span class="spos-gb-amount" id="dispTotal">0 TSh</span>
          </div>

          {{-- Payment Methods Grid --}}
          <div class="spos-pay-methods-grid">
            <div class="spos-pay-chip active" data-method="Cash" onclick="selectPayment('Cash', this)">
              <i class="bi bi-cash-stack"></i>
              <span>{{ __('Cash') }}</span>
            </div>
            <div class="spos-pay-chip" data-method="Mpesa" onclick="selectPayment('Mpesa', this)">
              <i class="bi bi-phone"></i>
              <span>{{ __('Mobile Money') }}</span>
            </div>
            <div class="spos-pay-chip" data-method="Bank" onclick="selectPayment('Bank', this)">
              <i class="bi bi-bank"></i>
              <span>{{ __('Bank') }}</span>
            </div>
            <div class="spos-pay-chip" data-method="Credit" onclick="selectPayment('Credit', this)">
              <i class="bi bi-person-lines-fill"></i>
              <span>{{ __('Credit') }}</span>
            </div>
          </div>

          {{-- Mobile Money Sub-Panel with Official Brand Logos --}}
          <div class="spos-sub-payment-panel" id="mobileMoneySubPanel" style="display:none;">
            <div class="d-flex justify-content-between align-items-center mb-1">
              <span class="spos-sub-title"><i class="bi bi-phone me-1 text-primary"></i>{{ __('Select Mobile Network') }}</span>
            </div>
            <div class="spos-provider-chips" id="mobileProviderChips">
              <button type="button" class="spos-provider-chip active" data-provider="Vodacom M-Pesa" onclick="selectMobileProvider('Vodacom M-Pesa', this)">
                <span class="spos-brand-logo-badge badge-mpesa">M</span>
                <span>Vodacom M-Pesa</span>
              </button>
              <button type="button" class="spos-provider-chip" data-provider="Mixx by Yas" onclick="selectMobileProvider('Mixx by Yas', this)">
                <span class="spos-brand-logo-badge badge-yas">Y</span>
                <span>Mixx by Yas</span>
              </button>
              <button type="button" class="spos-provider-chip" data-provider="Airtel Money" onclick="selectMobileProvider('Airtel Money', this)">
                <span class="spos-brand-logo-badge badge-airtel">A</span>
                <span>Airtel Money</span>
              </button>
              <button type="button" class="spos-provider-chip" data-provider="HaloPesa" onclick="selectMobileProvider('HaloPesa', this)">
                <span class="spos-brand-logo-badge badge-halotel">H</span>
                <span>HaloPesa</span>
              </button>
            </div>
            <input type="text" id="mobileRefInput" class="form-control form-control-sm mt-1" style="font-size:0.75rem;border-radius:6px;" placeholder="{{ __('Transaction Reference / Phone (Optional)') }}" oninput="updatePaymentDetails()">
          </div>

          {{-- Bank Sub-Panel with Official Bank Logos --}}
          <div class="spos-sub-payment-panel" id="bankSubPanel" style="display:none;">
            <div class="d-flex justify-content-between align-items-center mb-1">
              <span class="spos-sub-title"><i class="bi bi-bank me-1 text-primary"></i>{{ __('Select Bank') }}</span>
            </div>
            <div class="spos-provider-chips" id="bankProviderChips">
              <button type="button" class="spos-provider-chip active" data-provider="CRDB" onclick="selectBankProvider('CRDB', this)">
                <span class="spos-brand-logo-badge badge-crdb">CRDB</span>
                <span>CRDB Bank</span>
              </button>
              <button type="button" class="spos-provider-chip" data-provider="NMB" onclick="selectBankProvider('NMB', this)">
                <span class="spos-brand-logo-badge badge-nmb">NMB</span>
                <span>NMB Bank</span>
              </button>
              <button type="button" class="spos-provider-chip" data-provider="NBC" onclick="selectBankProvider('NBC', this)">
                <span class="spos-brand-logo-badge badge-nbc">NBC</span>
                <span>NBC Bank</span>
              </button>
              <button type="button" class="spos-provider-chip" data-provider="Stanbic" onclick="selectBankProvider('Stanbic', this)">
                <span class="spos-brand-logo-badge badge-stanbic"><i class="bi bi-shield-fill"></i></span>
                <span>Stanbic Bank</span>
              </button>
              <button type="button" class="spos-provider-chip" data-provider="KCB" onclick="selectBankProvider('KCB', this)">
                <span class="spos-brand-logo-badge badge-kcb">KCB</span>
                <span>KCB Bank</span>
              </button>
              <button type="button" class="spos-provider-chip" data-provider="Absa" onclick="selectBankProvider('Absa', this)">
                <span class="spos-brand-logo-badge badge-absa">absa</span>
                <span>Absa Bank</span>
              </button>
              <button type="button" class="spos-provider-chip" data-provider="Equity" onclick="selectBankProvider('Equity', this)">
                <span class="spos-brand-logo-badge badge-equity"><i class="bi bi-house-door-fill"></i></span>
                <span>Equity Bank</span>
              </button>
              <button type="button" class="spos-provider-chip" data-provider="DTB" onclick="selectBankProvider('DTB', this)">
                <span class="spos-brand-logo-badge badge-dtb">DTB</span>
                <span>DTB Bank</span>
              </button>
              <button type="button" class="spos-provider-chip" data-provider="Bank Transfer" onclick="selectBankProvider('Bank Transfer', this)">
                <span class="spos-brand-logo-badge badge-otherbank"><i class="bi bi-bank2"></i></span>
                <span>{{ __('Other Bank') }}</span>
              </button>
            </div>
            <input type="text" id="bankRefInput" class="form-control form-control-sm mt-1" style="font-size:0.75rem;border-radius:6px;" placeholder="{{ __('Deposit Slip / Reference No. (Optional)') }}" oninput="updatePaymentDetails()">
          </div>

          {{-- Amount Paid & Change Display --}}
          <div class="spos-tender-box">
            <div class="spos-tender-input-wrap">
              <div class="spos-tender-label">{{ __('Amount Paid (TSh)') }}</div>
              <input type="number" id="paidAmountDisplay" class="spos-tender-input" value="0" min="0" oninput="handlePaidInput(this.value)">
            </div>
            <div class="spos-change-display">
              <div class="spos-change-label" id="changeLabel">{{ __('Change') }}</div>
              <div class="spos-change-val" id="changeAmt">0 TSh</div>
            </div>
          </div>

          {{-- High-End Numeric Keypad (Numpad) --}}
          <div class="spos-numpad-grid" id="sposNumpad">
            <button type="button" class="spos-np-btn" onclick="numpadPress('7')">7</button>
            <button type="button" class="spos-np-btn" onclick="numpadPress('8')">8</button>
            <button type="button" class="spos-np-btn" onclick="numpadPress('9')">9</button>
            <button type="button" class="spos-np-btn" onclick="numpadPress('4')">4</button>
            <button type="button" class="spos-np-btn" onclick="numpadPress('5')">5</button>
            <button type="button" class="spos-np-btn" onclick="numpadPress('6')">6</button>
            <button type="button" class="spos-np-btn" onclick="numpadPress('1')">1</button>
            <button type="button" class="spos-np-btn" onclick="numpadPress('2')">2</button>
            <button type="button" class="spos-np-btn" onclick="numpadPress('3')">3</button>
            <button type="button" class="spos-np-btn clear-key" onclick="numpadPress('C')">C</button>
            <button type="button" class="spos-np-btn" onclick="numpadPress('0')">0</button>
            <button type="button" class="spos-np-btn" onclick="numpadPress('00')">00</button>
          </div>
        </div>
      </div>

      {{-- PINNED CHECKOUT BOTTOM BAR (ALWAYS VISIBLE & UNMISSABLE) --}}
      <div class="spos-pinned-checkout-bar">
        <button type="button" class="spos-btn-complete-massive" id="completeBtn" onclick="submitSale('sale')" title="{{ __('Complete Sale') }}">
          <div class="spos-comp-btn-left">
            <i class="bi bi-check-circle-fill"></i>
            <span>{{ __('Complete Sale') }}</span>
          </div>
          <span class="spos-comp-total-pill" id="compBtnTotalBadge">0 TSh</span>
        </button>
        <div class="spos-pinned-sub-actions">
          <button type="button" class="spos-btn-pinned-hold" onclick="holdSale()" title="{{ __('Hold Sale') }}">
            <i class="bi bi-pause-circle"></i>
            <span>{{ __('Hold') }}</span>
          </button>
          <button type="button" class="spos-btn-pinned-proforma" id="proformaBtn" onclick="submitSale('proforma')" title="{{ __('Save as Pro-forma') }}">
            <i class="bi bi-file-earmark-text"></i>
            <span>{{ __('Save as Pro-forma') }}</span>
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- ITEM DISCOUNT MODAL --}}
<div class="modal fade" id="discountModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <div class="modal-header border-bottom pb-2">
        <h6 class="modal-title fw-bold text-dark">
          <i class="bi bi-tag-fill me-2 text-warning"></i>{{ __('Item Discount') }}
        </h6>
        <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body py-3">
        <div id="discItemName" class="fw-bold text-dark mb-3 small"></div>
        <div class="mb-3">
          <label class="form-label small fw-semibold text-muted">{{ __('Discount Type') }}</label>
          <div class="btn-group w-100">
            <button type="button" class="btn btn-outline-secondary btn-sm active" id="discTypeFixed" onclick="setDiscType('fixed')">{{ __('Fixed Amount') }}</button>
            <button type="button" class="btn btn-outline-secondary btn-sm" id="discTypePercent" onclick="setDiscType('percent')">{{ __('Percentage') }}</button>
          </div>
        </div>
        <div class="mb-3">
          <label class="form-label small fw-semibold text-muted" id="discAmtLabel">{{ __('Amount Paid (TSh)') }}</label>
          <input type="number" id="discAmtInput" class="form-control fw-bold" min="0" value="0">
        </div>
        <div class="d-flex gap-1" id="quickPercentRow" style="display:none !important;">
          <button type="button" class="btn btn-light btn-sm flex-fill border" onclick="setQuickPercent(5)">5%</button>
          <button type="button" class="btn btn-light btn-sm flex-fill border" onclick="setQuickPercent(10)">10%</button>
          <button type="button" class="btn btn-light btn-sm flex-fill border" onclick="setQuickPercent(15)">15%</button>
          <button type="button" class="btn btn-light btn-sm flex-fill border" onclick="setQuickPercent(20)">20%</button>
        </div>
      </div>
      <div class="modal-footer border-0 pt-0">
        <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
        <button type="button" class="btn btn-primary btn-sm fw-bold" onclick="applyDiscount()">
          <i class="bi bi-check2 me-1"></i>{{ __('Apply Discount') }}
        </button>
      </div>
    </div>
  </div>
</div>

{{-- HELD SALES MODAL --}}
<div class="modal fade" id="heldSalesModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <div class="modal-header border-bottom pb-2">
        <h6 class="modal-title fw-bold text-dark">
          <i class="bi bi-pause-circle-fill me-2 text-warning"></i>{{ __('Held Sales') }}
        </h6>
        <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-0">
        <div class="list-group list-group-flush" id="heldSalesList">
          {{-- Populated dynamically --}}
        </div>
        <div class="p-4 text-center text-muted" id="emptyHeldMsg">
          <i class="bi bi-inbox fs-1 d-block mb-2 text-muted opacity-50"></i>
          <div>{{ __('No held sales yet') }}</div>
        </div>
      </div>
      <div class="modal-footer border-0 pt-2">
        <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">{{ __('Close') }}</button>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
/* ========================================================
   ENTERPRISE SUPERMARKET POS ENGINE
   ======================================================== */
let cart = [];
let itemIndex = 0;
let heldSales = [];
let currentDiscItemId = null;
let discType = 'fixed';
let currentPaymentMethod = 'Cash';
let soundEnabled = localStorage.getItem('spos_sound') !== '0';
let audioCtx = null;
let scanTimer = null;

const barcodeInput = document.getElementById('barcodeInput');
const barcodeClearBtn = document.getElementById('barcodeClearBtn');
const cartBody = document.getElementById('cartBody');
const emptyMsg = document.getElementById('emptyMsg');
const sposContainer = document.getElementById('sposContainer');

// Load held sales from localStorage
try {
  const savedHeld = localStorage.getItem('spos_held_sales');
  if (savedHeld) heldSales = JSON.parse(savedHeld) || [];
} catch(e) { heldSales = []; }
updateHeldBadge();
updateSoundUi();

/* ---------------- Audio Synthesizer ---------------- */
function playBeep(type = 'success') {
  if (!soundEnabled) return;
  try {
    if (!audioCtx) audioCtx = new (window.AudioContext || window.webkitAudioContext)();
    if (audioCtx.state === 'suspended') audioCtx.resume();
    
    const osc = audioCtx.createOscillator();
    const gain = audioCtx.createGain();
    osc.connect(gain);
    gain.connect(audioCtx.destination);
    
    if (type === 'success') {
      osc.type = 'sine';
      osc.frequency.setValueAtTime(880, audioCtx.currentTime);
      gain.gain.setValueAtTime(0.08, audioCtx.currentTime);
      gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.09);
      osc.start();
      osc.stop(audioCtx.currentTime + 0.09);
    } else {
      osc.type = 'triangle';
      osc.frequency.setValueAtTime(260, audioCtx.currentTime);
      gain.gain.setValueAtTime(0.12, audioCtx.currentTime);
      gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.18);
      osc.start();
      osc.stop(audioCtx.currentTime + 0.18);
    }
  } catch(e) {}
}

function toggleSound() {
  soundEnabled = !soundEnabled;
  localStorage.setItem('spos_sound', soundEnabled ? '1' : '0');
  updateSoundUi();
  if (soundEnabled) playBeep('success');
}

function updateSoundUi() {
  const icon = document.getElementById('soundIcon');
  const txt = document.getElementById('soundText');
  if (soundEnabled) {
    icon.className = 'bi bi-volume-up text-primary';
    if (txt) txt.textContent = "{{ __('Sound On') }}";
  } else {
    icon.className = 'bi bi-volume-mute text-muted';
    if (txt) txt.textContent = "{{ __('Sound Off') }}";
  }
}

/* ---------------- Mobile View Switcher ---------------- */
function switchMobileView(view) {
  if (sposContainer) sposContainer.setAttribute('data-mobile-view', view);
  document.getElementById('mtabCatalogBtn').classList.toggle('active', view === 'catalog');
  document.getElementById('mtabCartBtn').classList.toggle('active', view === 'cart');
  if (view === 'catalog' && barcodeInput) {
    setTimeout(() => barcodeInput.focus(), 100);
  }
}

/* ---------------- Category Filtering ---------------- */
document.querySelectorAll('.spos-cat-chip').forEach(chip => {
  chip.addEventListener('click', function() {
    document.querySelectorAll('.spos-cat-chip').forEach(c => c.classList.remove('active'));
    this.classList.add('active');
    filterProducts();
  });
});

/* ---------------- Search & Live Filter ---------------- */
barcodeInput.addEventListener('input', function() {
  clearTimeout(scanTimer);
  const val = this.value.trim();
  barcodeClearBtn.style.display = val ? 'block' : 'none';

  if (!val) {
    filterProducts();
    return;
  }

  // Fast barcode match if length >= 6
  scanTimer = setTimeout(() => {
    const directMatch = document.querySelector(`.spos-pcard[data-barcode="${val}"]`);
    if (directMatch && directMatch.dataset.disabled !== '1') {
      handleProductClick(directMatch);
      clearSearchInput();
      return;
    }
    filterProducts();
  }, val.length >= 6 ? 70 : 250);
});

barcodeInput.addEventListener('keydown', function(e) {
  if (e.key !== 'Enter') return;
  e.preventDefault();
  clearTimeout(scanTimer);
  const val = this.value.trim();
  if (!val) return;

  // Exact barcode match first
  let match = document.querySelector(`.spos-pcard[data-barcode="${val}"]`);
  if (!match) {
    // Exact or partial name match
    const available = Array.from(document.querySelectorAll('.spos-pcard:not([data-disabled="1"])'));
    match = available.find(p => p.dataset.barcode.toLowerCase() === val.toLowerCase() || p.dataset.name.toLowerCase().includes(val.toLowerCase()));
  }

  if (match && match.dataset.disabled !== '1') {
    handleProductClick(match);
    clearSearchInput();
  } else {
    playBeep('error');
  }
});

function clearSearchInput() {
  barcodeInput.value = '';
  barcodeClearBtn.style.display = 'none';
  filterProducts();
  barcodeInput.focus();
}

function filterProducts() {
  const activeCat = document.querySelector('.spos-cat-chip.active')?.dataset.cat || 'ALL';
  const query = barcodeInput.value.trim().toLowerCase();
  let visibleCount = 0;

  document.querySelectorAll('.spos-pcard').forEach(card => {
    const catMatch = (activeCat === 'ALL' || card.dataset.cat === activeCat);
    const textMatch = !query || card.dataset.name.toLowerCase().includes(query) || card.dataset.barcode.toLowerCase().includes(query);
    const show = catMatch && textMatch;
    card.style.display = show ? '' : 'none';
    if (show) visibleCount++;
  });

  const emptyNotice = document.getElementById('emptyCatalogMsg');
  if (emptyNotice) emptyNotice.style.display = visibleCount === 0 ? 'block' : 'none';
}

/* ---------------- Add to Cart & Scanned Product Banner ---------------- */
function handleProductClick(card) {
  if (card.dataset.disabled === '1') {
    playBeep('error');
    alert("{{ __('Cannot exceed available stock') }}");
    return;
  }
  const image = card.dataset.image || '';
  addToCart(
    card.dataset.id,
    card.dataset.name,
    parseFloat(card.dataset.price),
    parseInt(card.dataset.stock),
    card.dataset.expiry || '',
    card.dataset.expired === '1',
    image,
    card.dataset.barcode || ''
  );
  showScannedProduct(card.dataset.name, card.dataset.barcode || '', card.dataset.price, image);
  // Visual pulse
  card.style.transform = 'scale(0.96)';
  setTimeout(() => card.style.transform = '', 120);
}

function showScannedProduct(name, barcode, price, image) {
  const banner = document.getElementById('scannedProductBanner');
  if (!banner) return;
  document.getElementById('sbProductName').textContent = name;
  const codeEl = document.getElementById('sbProductCode');
  if (barcode) {
    codeEl.innerHTML = `<i class="bi bi-upc me-1"></i>${escapeHtml(barcode)}`;
    codeEl.style.display = 'inline-block';
  } else {
    codeEl.style.display = 'none';
  }
  document.getElementById('sbProductPrice').textContent = Number(price).toLocaleString() + ' TSh';

  const imgEl = document.getElementById('sbProductImg');
  const iconEl = document.getElementById('sbFallbackIcon');
  if (image) {
    imgEl.src = image;
    imgEl.style.display = 'block';
    iconEl.style.display = 'none';
  } else {
    imgEl.style.display = 'none';
    iconEl.style.display = 'block';
  }

  banner.style.display = 'flex';
}

function closeScannedBanner() {
  const banner = document.getElementById('scannedProductBanner');
  if (banner) banner.style.display = 'none';
}

function addToCart(id, name, price, stock, expiry, isExpired, image = '', barcode = '') {
  if (Math.abs(price - Math.round(price)) < 0.1) {
    price = Math.round(price);
  }
  if (isExpired && !confirm("{{ __('This product has expired. Are you sure you want to proceed?') }} (" + expiry + ")")) {
    return;
  }
  let existing = cart.find(i => String(i.id) === String(id));
  if (existing) {
    if (existing.qty >= stock) {
      playBeep('error');
      alert("{{ __('Cannot exceed available stock') }} (Max: " + stock + ")");
      return;
    }
    existing.qty++;
  } else {
    cart.push({
      id: String(id),
      name: name,
      price: price,
      qty: 1,
      stock: stock,
      expiry: expiry,
      isExpired: isExpired,
      image: image,
      barcode: barcode,
      discount: 0,
      discType: 'fixed',
      index: itemIndex++
    });
  }
  playBeep('success');
  renderCart();
  updateTotals();
}

/* ---------------- Cart Event Delegation ---------------- */
cartBody.addEventListener('click', function(e) {
  const btn = e.target.closest('[data-cart-action]');
  if (!btn) return;
  const action = btn.dataset.cartAction;
  const id = btn.dataset.id;
  if (!id) return;

  if (action === 'increase') changeQty(id, 1);
  else if (action === 'decrease') changeQty(id, -1);
  else if (action === 'remove') removeItem(id);
  else if (action === 'discount') openDiscountModal(id);
});

cartBody.addEventListener('change', function(e) {
  if (e.target.classList.contains('spos-step-input')) {
    const id = e.target.dataset.id;
    let val = parseInt(e.target.value) || 1;
    const item = cart.find(i => String(i.id) === String(id));
    if (!item) return;
    if (val > item.stock) {
      playBeep('error');
      alert("{{ __('Cannot exceed available stock') }} (Max: " + item.stock + ")");
      val = item.stock;
    }
    if (val < 1) val = 1;
    item.qty = val;
    renderCart();
    updateTotals();
  }
});

function changeQty(id, delta) {
  const item = cart.find(i => String(i.id) === String(id));
  if (!item) return;
  const nextQty = item.qty + delta;
  if (nextQty < 1) {
    removeItem(id);
    return;
  }
  if (nextQty > item.stock) {
    playBeep('error');
    alert("{{ __('Cannot exceed available stock') }} (Max: " + item.stock + ")");
    return;
  }
  item.qty = nextQty;
  renderCart();
  updateTotals();
}

function removeItem(id) {
  cart = cart.filter(i => String(i.id) !== String(id));
  renderCart();
  updateTotals();
}

function clearCart() {
  if (cart.length === 0) return;
  if (!confirm("{{ __('Are you sure you want to clear the cart?') }}")) return;
  cart = [];
  closeScannedBanner();
  renderCart();
  updateTotals();
}

/* ---------------- Render Cart ---------------- */
function renderCart() {
  if (cart.length === 0) {
    emptyMsg.style.display = 'block';
    // Remove all row items
    cartBody.querySelectorAll('.spos-cart-row').forEach(r => r.remove());
    return;
  }
  emptyMsg.style.display = 'none';

  // Build markup
  let html = '';
  cart.forEach(item => {
    const gross = item.price * item.qty;
    const disc = item.discType === 'percent' ? (gross * item.discount / 100) : (item.discount * item.qty);
    const lineTotal = Math.max(0, gross - disc);
    const discLabel = item.discount > 0
      ? `<span class="spos-crow-disc">-${item.discType === 'percent' ? item.discount + '%' : Number(item.discount).toLocaleString() + ' TSh'}</span>`
      : '';

    const thumbHtml = item.image
      ? `<div class="spos-crow-thumb"><img src="${escapeHtml(item.image)}" alt="${escapeHtml(item.name)}"></div>`
      : `<div class="spos-crow-thumb fallback"><i class="bi bi-box-seam"></i></div>`;

    html += `
      <div class="spos-cart-row" id="crow_${item.id}">
        ${thumbHtml}
        <div class="spos-crow-info">
          <div class="spos-crow-name" title="${escapeHtml(item.name)}">${escapeHtml(item.name)}</div>
          <div class="spos-crow-meta">
            <span class="spos-crow-price">${item.price.toLocaleString()} TSh</span>
            ${discLabel}
          </div>
          <div class="spos-stepper">
            <button type="button" class="spos-step-btn" data-cart-action="decrease" data-id="${item.id}">−</button>
            <input type="number" class="spos-step-input" value="${item.qty}" min="1" max="${item.stock}" data-id="${item.id}">
            <button type="button" class="spos-step-btn" data-cart-action="increase" data-id="${item.id}">+</button>
            <button type="button" class="spos-disc-trigger" data-cart-action="discount" data-id="${item.id}">% {{ __('Discount') }}</button>
          </div>
        </div>
        <div class="spos-crow-right">
          <button type="button" class="spos-crow-remove" data-cart-action="remove" data-id="${item.id}" title="{{ __('Delete') }}">
            <i class="bi bi-x-circle-fill"></i>
          </button>
          <div class="spos-crow-total">${lineTotal.toLocaleString()} TSh</div>
        </div>
      </div>
    `;
  });

  // Preserve emptyMsg inside cartBody and append rows
  const existingRows = cartBody.querySelectorAll('.spos-cart-row');
  existingRows.forEach(r => r.remove());
  emptyMsg.insertAdjacentHTML('afterend', html);
}

/* ---------------- Totals & Tender Calculations ---------------- */
function updateTotals() {
  let subtotal = 0;
  let totalDiscount = 0;
  let totalQty = 0;

  cart.forEach(item => {
    const gross = item.price * item.qty;
    const disc = item.discType === 'percent' ? (gross * item.discount / 100) : (item.discount * item.qty);
    subtotal += gross;
    totalDiscount += disc;
    totalQty += item.qty;
  });

  const grandTotal = Math.max(0, subtotal - totalDiscount);

  // Update displays
  document.getElementById('dispSubtotal').textContent = subtotal.toLocaleString() + ' TSh';
  const discRow = document.getElementById('dispDiscountRow');
  if (totalDiscount > 0) {
    discRow.style.display = 'flex';
    document.getElementById('dispDiscount').textContent = '-' + totalDiscount.toLocaleString() + ' TSh';
  } else {
    discRow.style.display = 'none';
  }
  document.getElementById('dispTotal').textContent = grandTotal.toLocaleString() + ' TSh';
  document.getElementById('cartCountBadge').textContent = totalQty;
  document.getElementById('mtabCartBadge').textContent = totalQty;

  // Floating mobile bar
  const mobBar = document.getElementById('mobileBottomBar');
  if (mobBar) {
    if (cart.length > 0) mobBar.classList.add('has-items');
    else mobBar.classList.remove('has-items');
    document.getElementById('mbbCount').textContent = totalQty + " {{ __('Items') }}";
    document.getElementById('mbbTotal').textContent = grandTotal.toLocaleString() + ' TSh';
  }

  // Tender defaults
  const paidInp = document.getElementById('paidAmountDisplay');
  // Default paid amount to total if previous was 0 or matched total
  if (parseFloat(paidInp.value) === 0 || paidInp.dataset.autoFilled === '1') {
    paidInp.value = grandTotal;
    paidInp.dataset.autoFilled = '1';
  }
  document.getElementById('hiddenPaid').value = paidInp.value;

  // Update Complete button live total pill
  const compBadge = document.getElementById('compBtnTotalBadge');
  if (compBadge) compBadge.textContent = grandTotal.toLocaleString() + ' TSh';

  // Action buttons status
  const canSubmit = cart.length > 0;
  const compBtn = document.getElementById('completeBtn');
  if (compBtn) {
    compBtn.disabled = false;
    compBtn.style.opacity = canSubmit ? '1' : '0.88';
  }
  const profBtn = document.getElementById('proformaBtn');
  if (profBtn) profBtn.disabled = false;

  calcChange(grandTotal);
}

function handlePaidInput(val) {
  document.getElementById('paidAmountDisplay').dataset.autoFilled = '0';
  document.getElementById('hiddenPaid').value = val;
  const tot = parseFloat(document.getElementById('dispTotal').textContent.replace(/[^0-9.]/g, '')) || 0;
  calcChange(tot);
}

function calcChange(total) {
  const paid = parseFloat(document.getElementById('paidAmountDisplay').value) || 0;
  const diff = paid - total;
  const changeEl = document.getElementById('changeAmt');
  const labelEl = document.getElementById('changeLabel');

  if (diff >= 0) {
    labelEl.textContent = "{{ __('Change') }}";
    changeEl.textContent = diff.toLocaleString() + ' TSh';
    changeEl.className = 'spos-change-val';
  } else {
    labelEl.textContent = "{{ __('Balance Due') }}";
    changeEl.textContent = Math.abs(diff).toLocaleString() + ' TSh';
    changeEl.className = 'spos-change-val due';
  }
}

let selectedMobileProvider = 'Vodacom M-Pesa';
let selectedBankProvider = 'CRDB';

function selectPayment(method, btn) {
  currentPaymentMethod = method;
  document.querySelectorAll('.spos-pay-chip').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');

  const mobPanel = document.getElementById('mobileMoneySubPanel');
  const bankPanel = document.getElementById('bankSubPanel');

  if (method === 'Mpesa') {
    if (mobPanel) mobPanel.style.display = 'flex';
    if (bankPanel) bankPanel.style.display = 'none';
  } else if (method === 'Bank') {
    if (mobPanel) mobPanel.style.display = 'none';
    if (bankPanel) bankPanel.style.display = 'flex';
  } else {
    if (mobPanel) mobPanel.style.display = 'none';
    if (bankPanel) bankPanel.style.display = 'none';
  }

  updatePaymentDetails();
}

function selectMobileProvider(providerName, btn) {
  selectedMobileProvider = providerName;
  document.querySelectorAll('#mobileProviderChips .spos-provider-chip').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
  updatePaymentDetails();
}

function selectBankProvider(bankName, btn) {
  selectedBankProvider = bankName;
  document.querySelectorAll('#bankProviderChips .spos-provider-chip').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
  updatePaymentDetails();
}

function updatePaymentDetails() {
  const hiddenPay = document.getElementById('hiddenPayment');
  if (!hiddenPay) return;

  if (currentPaymentMethod === 'Mpesa') {
    const ref = document.getElementById('mobileRefInput')?.value.trim();
    hiddenPay.value = ref ? `${selectedMobileProvider} (${ref})` : selectedMobileProvider;
  } else if (currentPaymentMethod === 'Bank') {
    const ref = document.getElementById('bankRefInput')?.value.trim();
    hiddenPay.value = ref ? `${selectedBankProvider} (${ref})` : selectedBankProvider;
  } else {
    hiddenPay.value = currentPaymentMethod;
  }
}

function numpadPress(val) {
  const paidInp = document.getElementById('paidAmountDisplay');
  if (!paidInp) return;

  const grandTotal = parseFloat(document.getElementById('dispTotal').textContent.replace(/[^0-9.]/g, '')) || 0;

  if (val === 'C') {
    paidInp.value = '0';
    paidInp.dataset.autoFilled = '0';
  } else {
    let currentVal = paidInp.value.toString();
    if (paidInp.dataset.autoFilled === '1' || currentVal === '0') {
      currentVal = (val === '00') ? '0' : val;
      paidInp.dataset.autoFilled = '0';
    } else {
      if (currentVal.length < 12) {
        currentVal += val;
      }
    }
    paidInp.value = currentVal;
  }

  document.getElementById('hiddenPaid').value = paidInp.value;
  calcChange(grandTotal);
}

/* ---------------- Form Submission ---------------- */
function submitSale(action = 'sale') {
  if (cart.length === 0) {
    playBeep('error');
    alert("{{ __('Please add products to cart first!') }}");
    return;
  }

  const container = document.getElementById('cartHiddenInputs');
  container.innerHTML = '';

  cart.forEach((item, idx) => {
    const gross = item.price * item.qty;
    const disc = item.discType === 'percent' ? (gross * item.discount / 100) : (item.discount * item.qty);
    const netUnitPrice = Math.max(0, (gross - disc) / item.qty).toFixed(2);

    container.innerHTML += `
      <input type="hidden" name="items[${idx}][product_id]" value="${item.id}">
      <input type="hidden" name="items[${idx}][quantity]" value="${item.qty}">
      <input type="hidden" name="items[${idx}][price]" value="${netUnitPrice}">
    `;
  });

  updatePaymentDetails();

  document.getElementById('hiddenAction').value = action;
  document.getElementById('hiddenCustomer').value = document.getElementById('customerSelect').value || '';
  document.getElementById('hiddenPaid').value = document.getElementById('paidAmountDisplay').value || '0';

  // Disable buttons to avoid double submission
  document.getElementById('completeBtn').disabled = true;
  document.getElementById('proformaBtn').disabled = true;
  document.getElementById('posForm').submit();
}

/* ---------------- Held Sales Workflow ---------------- */
function holdSale() {
  if (cart.length === 0) return;
  const summary = cart.map(i => `${i.name} (x${i.qty})`).join(', ');
  const total = parseFloat(document.getElementById('dispTotal').textContent.replace(/[^0-9.]/g, '')) || 0;
  const custName = document.getElementById('customerSelect').options[document.getElementById('customerSelect').selectedIndex]?.text || "{{ __('Walk-in Customer') }}";

  heldSales.push({
    id: Date.now(),
    time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }),
    date: new Date().toLocaleDateString(),
    customer_id: document.getElementById('customerSelect').value,
    customer_name: custName,
    summary: summary,
    total: total,
    cart: JSON.parse(JSON.stringify(cart)),
    itemIndex: itemIndex
  });

  // Persist to localStorage
  try { localStorage.setItem('spos_held_sales', JSON.stringify(heldSales)); } catch(e) {}

  cart = [];
  renderCart();
  updateTotals();
  updateHeldBadge();
  playBeep('success');
  if (barcodeInput) barcodeInput.focus();
}

function updateHeldBadge() {
  const badge = document.getElementById('heldCountBadge');
  const btn = document.getElementById('heldSalesBtn');
  if (!badge || !btn) return;

  if (heldSales.length > 0) {
    badge.textContent = heldSales.length;
    badge.style.display = 'inline-block';
    btn.classList.add('active-held');
  } else {
    badge.style.display = 'none';
    btn.classList.remove('active-held');
  }
}

function openHeldSalesModal() {
  const listEl = document.getElementById('heldSalesList');
  const emptyEl = document.getElementById('emptyHeldMsg');
  listEl.innerHTML = '';

  if (heldSales.length === 0) {
    emptyEl.style.display = 'block';
  } else {
    emptyEl.style.display = 'none';
    heldSales.forEach((held, idx) => {
      const itemEl = document.createElement('div');
      itemEl.className = 'list-group-item d-flex justify-content-between align-items-center p-3';
      itemEl.innerHTML = `
        <div style="flex:1;min-width:0;padding-right:12px;">
          <div class="d-flex align-items-center gap-2 mb-1">
            <span class="badge bg-light text-dark border">${held.time}</span>
            <strong class="text-dark small">${escapeHtml(held.customer_name)}</strong>
          </div>
          <div class="text-truncate text-muted" style="font-size:0.75rem;">${escapeHtml(held.summary)}</div>
          <div class="fw-bold text-success mt-1" style="font-size:0.85rem;">${held.total.toLocaleString()} TSh</div>
        </div>
        <div class="d-flex gap-2">
          <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteHeld(${idx})">
            <i class="bi bi-trash3"></i>
          </button>
          <button type="button" class="btn btn-sm btn-primary fw-bold" onclick="restoreHeld(${idx})">
            <i class="bi bi-box-arrow-in-down-left me-1"></i>{{ __('Restore') }}
          </button>
        </div>
      `;
      listEl.appendChild(itemEl);
    });
  }
  new bootstrap.Modal(document.getElementById('heldSalesModal')).show();
}

function restoreHeld(index) {
  if (cart.length > 0 && !confirm("{{ __('Are you sure you want to clear the cart?') }}")) {
    return;
  }
  const target = heldSales.splice(index, 1)[0];
  if (!target) return;

  cart = target.cart;
  itemIndex = target.itemIndex || cart.length;
  document.getElementById('customerSelect').value = target.customer_id || '';
  document.getElementById('hiddenCustomer').value = target.customer_id || '';

  try { localStorage.setItem('spos_held_sales', JSON.stringify(heldSales)); } catch(e) {}
  updateHeldBadge();
  renderCart();
  updateTotals();

  bootstrap.Modal.getInstance(document.getElementById('heldSalesModal')).hide();
  playBeep('success');
  if (barcodeInput) barcodeInput.focus();
}

function deleteHeld(index) {
  heldSales.splice(index, 1);
  try { localStorage.setItem('spos_held_sales', JSON.stringify(heldSales)); } catch(e) {}
  updateHeldBadge();
  openHeldSalesModal();
}

/* ---------------- Item Discount Modal ---------------- */
function openDiscountModal(id) {
  currentDiscItemId = id;
  const item = cart.find(i => String(i.id) === String(id));
  if (!item) return;

  document.getElementById('discItemName').textContent = item.name + ' (' + item.price.toLocaleString() + ' TSh)';
  document.getElementById('discAmtInput').value = item.discount || 0;
  setDiscType(item.discType || 'fixed');
  new bootstrap.Modal(document.getElementById('discountModal')).show();
}

function setDiscType(type) {
  discType = type;
  document.getElementById('discTypeFixed').classList.toggle('active', type === 'fixed');
  document.getElementById('discTypePercent').classList.toggle('active', type === 'percent');
  document.getElementById('discAmtLabel').textContent = type === 'fixed' ? "{{ __('Fixed Amount') }}" : "{{ __('Percentage') }}";
  document.getElementById('quickPercentRow').style.setProperty('display', type === 'percent' ? 'flex' : 'none', 'important');
}

function setQuickPercent(pct) {
  document.getElementById('discAmtInput').value = pct;
}

function applyDiscount() {
  const item = cart.find(i => String(i.id) === String(currentDiscItemId));
  if (!item) return;
  item.discount = parseFloat(document.getElementById('discAmtInput').value) || 0;
  item.discType = discType;

  bootstrap.Modal.getInstance(document.getElementById('discountModal')).hide();
  renderCart();
  updateTotals();
}

/* ---------------- Keyboard Shortcuts ---------------- */
document.addEventListener('keydown', function(e) {
  if (e.key === 'F2') {
    e.preventDefault();
    submitSale('sale');
  } else if (e.key === 'F3') {
    e.preventDefault();
    holdSale();
  } else if (e.key === 'F4') {
    e.preventDefault();
    clearCart();
  } else if (e.key === 'Escape') {
    if (barcodeInput) {
      clearSearchInput();
      barcodeInput.focus();
    }
  } else {
    // If user starts typing barcode and no input/modal is focused, redirect to barcodeInput
    const tag = e.target.tagName;
    if (tag !== 'INPUT' && tag !== 'TEXTAREA' && tag !== 'SELECT' && !document.querySelector('.modal.show')) {
      if (e.key.length === 1 && barcodeInput) {
        barcodeInput.focus();
      }
    }
  }
});

function escapeHtml(str) {
  if (!str) return '';
  return String(str).replace(/[&<>"']/g, function(m) {
    return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m];
  });
}
</script>
@endpush

@else
{{-- STANDARD POS --}}
<div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
  <div><h4 class="fw-bold mb-0 text-dark">{{ __('Point of Sale (POS)') }}</h4><span class="text-muted small">{{ __('Record new sales') }}</span></div>
  <div><a href="{{ route('sales.index') }}" class="btn btn-light px-4 shadow-sm" style="border-radius:8px;"><i class="bi bi-clock-history me-2"></i>{{ __('Sales History') }}</a></div>
</div>
<form action="{{ route('sales.store') }}" method="POST" id="posForm">
  @csrf
  <div class="row g-4">
    <div class="col-lg-8">
      <div class="card border-0 shadow-sm mb-4" style="border-radius:16px;">
        <div class="card-body p-4">
          <h6 class="fw-bold mb-3">{{ __('Products List') }}</h6>
          @if($isRestaurant)
            <div class="mb-4" style="max-height:500px;overflow-y:auto;">
              @foreach($groupedProducts as $categoryName => $catProducts)
                <div class="mb-4"><h6 class="fw-bold text-uppercase text-muted border-bottom pb-2">{{ $categoryName }}</h6>
                  <div class="d-flex flex-wrap gap-2 mt-2">
                    @foreach($catProducts as $p)
                      <button type="button" class="btn btn-outline-primary p-3 text-start product-btn position-relative" style="width:140px;height:100px;border-radius:12px;" data-id="{{ $p->id }}" data-name="{{ $p->name }}" data-price="{{ $p->selling_price }}" data-stock="{{ $p->track_stock?$p->current_stock:999999 }}" data-requires-imei="false" data-expiry="" data-is-expired="false">
                        <div class="fw-bold text-truncate" style="font-size:0.9rem;">{{ $p->name }}</div>
                        <div class="small fw-bold text-success position-absolute bottom-0 start-0 m-2">{{ number_format($p->selling_price) }}</div>
                      </button>
                    @endforeach
                  </div></div>
              @endforeach
            </div>
          @else
            <div class="row mb-3">
              <div class="col-12 col-md-8 mb-2 mb-md-0">
                <select id="productSelect" class="form-select border-0 shadow-sm bg-light" style="border-radius:8px;">
                  <option value="">{{ __('-- Search &amp; Select Product --') }}</option>
                  @foreach($products as $product)
                    @php
                        $cleanPrice = (abs($product->selling_price - round($product->selling_price)) < 0.1) ? (int)round($product->selling_price) : $product->selling_price;
                    @endphp
                    <option value="{{ $product->id }}" data-name="{{ $product->name }} {{ $product->model?'- '.$product->model:'' }}" data-price="{{ $cleanPrice }}" data-stock="{{ $product->track_stock?$product->current_stock:999999 }}" data-requires-imei="{{ ($showImei&&$product->requires_imei)?'true':'false' }}" data-expiry="{{ ($showExpiry&&$product->expiry_date)?\Carbon\Carbon::parse($product->expiry_date)->format('d/m/Y'):'' }}" data-is-expired="{{ ($showExpiry&&$product->expiry_date&&\Carbon\Carbon::parse($product->expiry_date)->isPast())?'true':'false' }}">{{ $product->name }} {{ $product->model?'- '.$product->model:'' }} (Stock: {{ $product->track_stock?$product->current_stock:'N/A' }}) - {{ number_format($cleanPrice) }} TSh</option>
                  @endforeach
                </select>
              </div>
              <div class="col-12 col-md-4"><button type="button" id="addItemBtn" class="btn btn-primary w-100" style="border-radius:8px;"><i class="bi bi-plus-lg"></i> {{ __('Add to Cart') }}</button></div>
            </div>
          @endif
          <div class="table-responsive">
            <table class="table align-middle" id="cartTable">
              <thead class="bg-light text-muted"><tr><th class="ps-3 border-0 rounded-start" style="min-width:250px;">{{ __('Product') }}</th><th class="border-0 text-center" style="min-width:120px;">{{ __('Qty') }}</th><th class="border-0 text-end" style="min-width:120px;">{{ __('Price') }}</th><th class="border-0 text-end" style="min-width:120px;">{{ __('Total') }}</th><th class="border-0 rounded-end text-center" style="min-width:50px;"></th></tr></thead>
              <tbody id="cartBody"><tr id="emptyCartRow"><td colspan="5" class="text-center py-5 text-muted"><i class="bi bi-cart-x fs-1 d-block mb-2"></i><p class="mb-0">{{ __('Cart is empty. Select products to sell.') }}</p></td></tr></tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-4"><div class="card border-0 shadow-sm mb-4" style="border-radius:16px;"><div class="card-body p-4">
      <h6 class="fw-bold mb-4">{{ __('Sale Details') }}</h6>
      <div class="mb-3"><label class="form-label text-muted small fw-medium">{{ __('Reference / Receipt No.') }}</label><input type="text" class="form-control bg-light border-0" value="{{ $reference_no }}" readonly></div>
      <div class="mb-3"><label class="form-label text-muted small fw-medium">{{ __('Date') }}</label><input type="date" name="sale_date" class="form-control" value="{{ date('Y-m-d') }}" required></div>
      <div class="mb-4"><label class="form-label text-muted small fw-medium">{{ __('Customer (Optional)') }}</label><select name="customer_id" class="form-select"><option value="">{{ __('Walk-in Customer (None)') }}</option>@foreach($customers as $customer)<option value="{{ $customer->id }}">{{ $customer->name }}</option>@endforeach</select></div>
      <hr><div class="d-flex justify-content-between mb-2"><span class="text-muted fw-medium">{{ __('Subtotal') }}</span><span class="fw-bold" id="cartSubtotal">0 TSh</span></div>
      <div class="d-flex justify-content-between mb-4"><h4 class="fw-bold mb-0">{{ __('Total') }}</h4><h4 class="fw-bold text-primary mb-0" id="cartTotal">0 TSh</h4></div><hr>
      <div class="mb-3"><label class="form-label fw-bold">{{ __('Amount Paid') }} (TSh) <span class="text-danger">*</span></label><input type="number" name="paid_amount" id="paidAmount" class="form-control form-control-lg fw-bold" value="0" min="0" required></div>
      <div class="mb-4"><label class="form-label text-muted small fw-medium">{{ __('Payment Method') }} <span class="text-danger">*</span></label><select name="payment_method" class="form-select"><option value="Cash">{{ __('Cash') }}</option><option value="Mpesa">Mpesa / Mobile Money</option><option value="Bank">{{ __('Bank Transfer') }}</option><option value="Credit">Credit (Deni)</option></select></div>
      <div class="mb-4"><label class="form-label text-muted small fw-medium">{{ __('Notes (Optional)') }}</label><textarea name="notes" class="form-control" rows="2"></textarea></div>
      <button type="submit" name="action" value="sale" class="btn btn-primary w-100 fw-bold py-2.5 shadow-sm d-flex align-items-center justify-content-center" style="border-radius:10px; font-size: 1rem; min-height: 48px;" id="submitBtn" disabled>
          <i class="bi bi-check-circle me-2 fs-5"></i><span>{{ __('Complete Sale') }}</span>
      </button>
      <button type="submit" name="action" value="proforma" class="btn btn-outline-primary w-100 fw-semibold mt-2 py-2 px-2 d-flex align-items-center justify-content-center" style="border-radius:10px; font-size: 0.88rem; min-height: 42px; white-space: normal; text-align: center; line-height: 1.2;" id="proformaBtn" disabled>
          <i class="bi bi-file-earmark-text me-2 fs-6"></i><span>{{ __('Save as Pro-forma') }}</span>
      </button>
    </div></div></div>
  </div>
</form>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function(){$('#productSelect').select2({placeholder:'-- Search & Select Product --',allowClear:true,width:'100%'});$('select[name="customer_id"]').select2({placeholder:'Walk-in Customer (None)',allowClear:true,width:'100%'});});
document.addEventListener('DOMContentLoaded',function(){
  let cart=[],itemIndex=0;
  const pSel=document.getElementById('productSelect'),addBtn=document.getElementById('addItemBtn'),cBody=document.getElementById('cartBody'),empRow=document.getElementById('emptyCartRow'),subEl=document.getElementById('cartSubtotal'),totEl=document.getElementById('cartTotal'),paidInp=document.getElementById('paidAmount'),subBtn=document.getElementById('submitBtn');
  document.querySelectorAll('.product-btn').forEach(btn=>{btn.addEventListener('click',function(){this.classList.add('bg-primary','text-white');setTimeout(()=>this.classList.remove('bg-primary','text-white'),150);let rawPrice=parseFloat(this.dataset.price);if(Math.abs(rawPrice-Math.round(rawPrice))<0.1) rawPrice=Math.round(rawPrice);addToCart(this.dataset.id,this.dataset.name,rawPrice,parseInt(this.dataset.stock),false,'',false);});});
  if(addBtn){addBtn.addEventListener('click',function(){if(!pSel.value)return;const o=pSel.options[pSel.selectedIndex];let rawPrice=parseFloat(o.dataset.price);if(Math.abs(rawPrice-Math.round(rawPrice))<0.1) rawPrice=Math.round(rawPrice);addToCart(o.value,o.dataset.name,rawPrice,parseInt(o.dataset.stock),o.dataset.requiresImei==='true',o.dataset.expiry,o.dataset.isExpired==='true');$('#productSelect').val(null).trigger('change');});}
  function addToCart(id,name,price,stock,req,exp,isExp){if(isExp&&!confirm('WARNING: Expired ('+exp+'). Continue?'))return;if(Math.abs(price-Math.round(price))<0.1) price=Math.round(price);let ex=cart.find(i=>i.id===id);if(ex){if(ex.qty>=stock){alert('Cannot exceed stock!');return;}ex.qty++;document.getElementById('qty_'+id).value=ex.qty;}else{cart.push({id,name,price,qty:1,stock,index:itemIndex,req,exp,isExp});renderRow(id,name,price,stock,itemIndex,req,exp,isExp);itemIndex++;}updT();}
  function renderRow(id,name,price,stock,idx,req,exp,isExp){if(empRow)empRow.style.display='none';let ex2='';if(exp)ex2+='<br><small class="'+(isExp?'text-danger fw-bold':'text-warning')+'">Exp: '+exp+'</small>';if(req)ex2+='<div class="mt-2"><input type="text" name="items['+idx+'][imei]" class="form-control border-primary" style="min-width:180px;" placeholder="Enter IMEI / Serial Number" required></div>';const tr=document.createElement('tr');tr.id='row_'+id;tr.innerHTML='<td class="ps-3 fw-bold text-dark">'+name+ex2+'<input type="hidden" name="items['+idx+'][product_id]" value="'+id+'"></td><td class="text-center"><input type="number" name="items['+idx+'][quantity]" id="qty_'+id+'" class="form-control form-control-sm text-center qty-input" value="1" min="1" max="'+stock+'" data-id="'+id+'"></td><td class="text-end"><input type="number" name="items['+idx+'][price]" class="form-control form-control-sm text-end price-input" value="'+price+'" min="0" step="any" data-id="'+id+'"></td><td class="text-end fw-bold" id="total_'+id+'">'+price.toLocaleString()+'</td><td class="text-center"><button type="button" class="btn btn-sm btn-light text-danger remove-btn" data-id="'+id+'"><i class="bi bi-x-lg"></i></button></td>';cBody.appendChild(tr);tr.querySelector('.qty-input').addEventListener('input',function(){let item=cart.find(i=>i.id===id),val=parseInt(this.value);if(val>stock){alert('Cannot exceed stock ('+stock+')');this.value=stock;val=stock;}if(val<1||isNaN(val))val=1;item.qty=val;updT();});tr.querySelector('.price-input').addEventListener('input',function(){let item=cart.find(i=>i.id===id);item.price=parseFloat(this.value)||0;updT();});tr.querySelector('.remove-btn').addEventListener('click',function(){cart=cart.filter(i=>i.id!==id);tr.remove();if(cart.length===0)empRow.style.display='table-row';updT();});}
  function updT(){let tot=0;cart.forEach(item=>{let t=item.price*item.qty;tot+=t;document.getElementById('total_'+item.id).innerText=t.toLocaleString();});subEl.innerText=tot.toLocaleString()+' TSh';totEl.innerText=tot.toLocaleString()+' TSh';paidInp.value=tot;subBtn.disabled=cart.length===0;document.getElementById('proformaBtn').disabled=cart.length===0;}
});
</script>
@endpush

@endif

@endsection
