<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('dashboard') }}" class="brand-link">
        <i class="fas fa-cash-register brand-image"></i>
        <span class="brand-text font-weight-light">Kasir Kafe Al Muhsin</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <i class="fas fa-user-circle fa-2x text-white"></i>
            </div>
            <div class="info">
                <a href="{{ route('profile.edit') }}" class="d-block">{{ auth()->user()->name }}</a>
                <small class="text-muted">{{ auth()->user()->roles->pluck('name')->first() ?? 'User' }}</small>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                <!-- Dashboard -->
                @can('view-dashboard')
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}"
                            class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                @endcan

                <!-- Transaksi -->
                @canany(['manage-transactions', 'view-transactions'])
                    <li class="nav-item {{ request()->routeIs('transactions.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('transactions.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-shopping-cart"></i>
                            <p>
                                Transaksi
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('manage-transactions')
                                <li class="nav-item">
                                    <a href="{{ route('transactions.create') }}"
                                        class="nav-link {{ request()->routeIs('transactions.create') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Kasir (Input Pesanan)</p>
                                    </a>
                                </li>
                            @endcan
                            @canany(['manage-transactions', 'view-transactions'])
                                <li class="nav-item">
                                    <a href="{{ route('transactions.index') }}"
                                        class="nav-link {{ request()->routeIs('transactions.index') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Riwayat Transaksi</p>
                                    </a>
                                </li>
                            @endcanany
                        </ul>
                    </li>
                @endcanany

                <!-- Master -->
                @can('manage-products')
                    <li class="nav-header">MASTER DATA</li>
                    <li class="nav-item">
                        <a href="{{ route('products.index') }}"
                            class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-box"></i>
                            <p>Produk</p>
                        </a>
                    </li>
                @endcan

                <!-- Stok Bahan -->
                @can('manage-stock')
                    <li class="nav-header">STOK BAHAN</li>
                    <li
                        class="nav-item {{ request()->routeIs('ingredients.*') || request()->routeIs('ingredient-logs.*') ? 'menu-open' : '' }}">
                        <a href="#"
                            class="nav-link {{ request()->routeIs('ingredients.*') || request()->routeIs('ingredient-logs.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-warehouse"></i>
                            <p>
                                Stok Bahan
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('ingredients.index') }}"
                                    class="nav-link {{ request()->routeIs('ingredients.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Data Bahan</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('ingredient-logs.index') }}"
                                    class="nav-link {{ request()->routeIs('ingredient-logs.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Log Stok</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan

                <!-- Laporan -->
                @can('view-reports')
                    <li class="nav-header">LAPORAN</li>
                    <li class="nav-item {{ request()->routeIs('reports.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-chart-bar"></i>
                            <p>
                                Laporan
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('reports.daily') }}"
                                    class="nav-link {{ request()->routeIs('reports.daily') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Harian</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('reports.monthly') }}"
                                    class="nav-link {{ request()->routeIs('reports.monthly') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Bulanan</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('reports.stock') }}"
                                    class="nav-link {{ request()->routeIs('reports.stock') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Stok Bahan</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan

                <!-- User & Akses -->
                @can('manage-users')
                    <li class="nav-header">USER & AKSES</li>
                    <li class="nav-item">
                        <a href="{{ route('users.index') }}"
                            class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Users</p>
                        </a>
                    </li>
                @endcan

            </ul>
        </nav>
    </div>
</aside>