<div class="main-sidebar">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            {{-- <a href="index.html">Stisla</a> --}}
            <a href="">
                <img src="{{ asset('assets/img/logo_app.png') }}" alt="logo" width="100" class="">
            </a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            {{-- <a href="index.html">St</a> --}}
            <a href="">
                <img src="{{ asset('assets/img/logo_app.png') }}" alt="logo" width="35" class="">
            </a>
        </div>
        <ul class="sidebar-menu">
            <li class="{{ request()->is('dashboard') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('dashboard') }}">
                    <i class="fas fa-fire"></i> <span>Dashboard</span>
                </a>
            </li>
            <li class="{{ request()->is('products*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('products.index') }}">
                    <i class="fas fa-box-open"></i> <span>Produk</span>
                </a>
            </li>
            <li class="{{ request()->is('cashier*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('cashier.index') }}">
                    <i class="fas fa-cash-register"></i> <span>Kasir</span>
                </a>
            </li>
        </ul>
    </aside>
</div>