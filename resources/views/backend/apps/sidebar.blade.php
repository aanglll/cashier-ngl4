<nav id="sidebar" class="sidebar js-sidebar">
    <div class="sidebar-content js-simplebar">
        <a class="sidebar-brand" href="{{ route('home') }}">
            <span class="align-middle">Kasirngl</span>
        </a>

        <ul class="sidebar-nav">
            <li class="sidebar-item {{ request()->routeIs('home') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('home') }}">
                    <i class="align-middle" data-feather="home"></i> <span class="align-middle">Dashboard</span>
                </a>
            </li>

            <li class="sidebar-header">
                {{-- Misc --}}
                Transaction
            </li>

            <li class="sidebar-item {{ request()->is('dashboard/sales*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('backend.sales.index') }}">
                    <i class="align-middle" data-feather="log-out"></i> <span class="align-middle">Sale</span>
                </a>
            </li>

            <li class="sidebar-item {{ request()->is('dashboard/purchases*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('backend.purchases.index') }}">
                    <i class="align-middle" data-feather="log-in"></i> <span class="align-middle">Purchase</span>
                </a>
            </li>

            <li class="sidebar-item {{ request()->is('dashboard/stock*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('backend.stocks.index') }}">
                    <i class="align-middle" data-feather="package"></i> <span class="align-middle">Stock</span>
                </a>
            </li>

            {{-- <li class="sidebar-header">
                Content
            </li>

            <li class="sidebar-item {{ request()->routeIs('media') ? 'active' : '' }}">
                <a class="sidebar-link" href="#">
                    <i class="align-middle" data-feather="camera"></i> <span class="align-middle">Media</span>
                </a>
            </li> --}}

            <li class="sidebar-header">
                {{-- General --}}
                Master Data
            </li>

            <li class="sidebar-item {{ request()->is('dashboard/product*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('backend.product') }}">
                    <i class="align-middle" data-feather="shopping-bag"></i> <span class="align-middle">Product</span>
                </a>
            </li>

            <li class="sidebar-item {{ request()->is('dashboard/supplier*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('backend.supplier.index') }}">
                    <i class="align-middle" data-feather="truck"></i> <span class="align-middle">Supplier</span>
                </a>
            </li>

            <li class="sidebar-item {{ request()->is('dashboard/customer*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('backend.customer.index') }}">
                    <i class="align-middle" data-feather="users"></i> <span class="align-middle">Customer</span>
                </a>
            </li>

            <li class="sidebar-item {{ request()->is('dashboard/settings*') ? 'active' : '' }}">
                <a class="sidebar-link" href="#">
                    <i class="align-middle" data-feather="settings"></i> <span class="align-middle">Setting</span>
                </a>
            </li>

            <li class="sidebar-item {{ request()->is('dashboard/users*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('backend.users.index') }}">
                    <i class="align-middle" data-feather="user"></i> <span class="align-middle">User</span>
                </a>
            </li>

            <li class="sidebar-item {{ request()->is('dashboard/role-permission*') ? 'active' : '' }}">
                <a class="sidebar-link" href="{{ route('role-permission.index') }}">
                    <i class="align-middle" data-feather="shield"></i> <span class="align-middle">Role & Permission</span>
                </a>
            </li>
        </ul>

    </div>
</nav>
