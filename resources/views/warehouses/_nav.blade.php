<nav class="gd-tabs mb-4" aria-label="{{ __('Warehouse navigation') }}">
    <a href="{{ route('warehouses.index') }}" class="gd-tab {{ request()->routeIs('warehouses.*') ? 'active' : '' }}">
        <i class="bi bi-buildings me-1"></i> {{ __('Warehouses') }}
    </a>
    <a href="{{ route('stock.transfer.create') }}" class="gd-tab {{ request()->routeIs('stock.transfer.*') ? 'active' : '' }}">
        <i class="bi bi-arrow-left-right me-1"></i> {{ __('Transfer Stock') }}
    </a>
    <a href="{{ route('stock.movements.index') }}" class="gd-tab {{ request()->routeIs('stock.movements.*') ? 'active' : '' }}">
        <i class="bi bi-clock-history me-1"></i> {{ __('Movement History') }}
    </a>
</nav>
