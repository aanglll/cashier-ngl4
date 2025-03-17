<nav id="sidebar" class="sidebar js-sidebar">
    <div class="sidebar-content js-simplebar">
        <a class="sidebar-brand" href="{{ route('home') }}">
            <span class="align-middle">{!! $settings->site_name !!}</span>
        </a>

        <ul class="sidebar-nav">
            @if (auth()->user()->can('view dashboards'))
                <li class="sidebar-item {{ request()->routeIs('home') ? 'active' : '' }}">
                    <a class="sidebar-link" href="{{ route('home') }}">
                        <i class="align-middle" data-feather="home"></i> <span class="align-middle">Dashboard</span>
                    </a>
                </li>
            @endif

            {{-- <li class="sidebar-header">
                Transaction
            </li> --}}

            @if (auth()->user()->can('view sales'))
                <li class="sidebar-item {{ request()->is('dashboard/sales*') ? 'active' : '' }}">
                    <a class="sidebar-link" href="{{ route('backend.sales.index') }}">
                        <i class="align-middle" data-feather="log-out"></i> <span class="align-middle">Sale</span>
                    </a>
                </li>
            @endif

            @if (auth()->user()->hasRole('officer'))
                <li class="sidebar-item {{ request()->is('dashboard/sales*') ? 'active' : '' }}">
                    <a class="sidebar-link" href="{{ route('backend.sales.create') }}">
                        <i class="align-middle" data-feather="log-out"></i> <span class="align-middle">Sale</span>
                    </a>
                </li>
            @endif

            @if (auth()->user()->can('view purchases'))
                <li class="sidebar-item {{ request()->is('dashboard/purchases*') ? 'active' : '' }}">
                    <a class="sidebar-link" href="{{ route('backend.purchases.index') }}">
                        <i class="align-middle" data-feather="log-in"></i> <span class="align-middle">Purchase</span>
                    </a>
                </li>
            @endif

            @if (auth()->user()->can('view stocks'))
                <li class="sidebar-item {{ request()->is('dashboard/stock*') ? 'active' : '' }}">
                    <a class="sidebar-link" href="{{ route('backend.stocks.index') }}">
                        <i class="align-middle" data-feather="package"></i> <span class="align-middle">Stock</span>
                    </a>
                </li>
            @endif

            {{-- <li class="sidebar-header">
                Content
            </li>

            <li class="sidebar-item {{ request()->routeIs('media') ? 'active' : '' }}">
                <a class="sidebar-link" href="#">
                    <i class="align-middle" data-feather="camera"></i> <span class="align-middle">Media</span>
                </a>
            </li> --}}

            {{-- <li class="sidebar-header">
                Master Data
            </li> --}}

            @if (auth()->user()->can('view products'))
                <li class="sidebar-item {{ request()->is('dashboard/product*') ? 'active' : '' }}">
                    <a class="sidebar-link" href="{{ route('backend.product') }}">
                        <i class="align-middle" data-feather="shopping-bag"></i> <span
                            class="align-middle">Product</span>
                    </a>
                </li>
            @endif

            @if (auth()->user()->can('view suppliers'))
                <li class="sidebar-item {{ request()->is('dashboard/supplier*') ? 'active' : '' }}">
                    <a class="sidebar-link" href="{{ route('backend.supplier.index') }}">
                        <i class="align-middle" data-feather="truck"></i> <span class="align-middle">Supplier</span>
                    </a>
                </li>
            @endif

            @if (auth()->user()->can('view customers'))
                <li class="sidebar-item {{ request()->is('dashboard/customer*') ? 'active' : '' }}">
                    <a class="sidebar-link" href="{{ route('backend.customer.index') }}">
                        <i class="align-middle" data-feather="users"></i> <span class="align-middle">Customer</span>
                    </a>
                </li>
            @endif

            @if (auth()->user()->can('view settings'))
                <li class="sidebar-item {{ request()->is('dashboard/settings*') ? 'active' : '' }}">
                    <a class="sidebar-link" href="{{ route('settings.edit') }}">
                        <i class="align-middle" data-feather="settings"></i> <span class="align-middle">Setting</span>
                    </a>
                </li>
            @endif

            @if (auth()->user()->can('view users'))
                <li class="sidebar-item {{ request()->is('dashboard/users*') ? 'active' : '' }}">
                    <a class="sidebar-link" href="{{ route('backend.users.index') }}">
                        <i class="align-middle" data-feather="user"></i> <span class="align-middle">User</span>
                    </a>
                </li>
            @endif

            @if (auth()->user()->can('view role'))
                <li class="sidebar-item {{ request()->is('dashboard/role-permission*') ? 'active' : '' }}">
                    <a class="sidebar-link" href="{{ route('role-permission.index') }}">
                        <i class="align-middle" data-feather="shield"></i> <span class="align-middle">Role &
                            Permission</span>
                    </a>
                </li>
            @endif
        </ul>

    </div>
</nav>
