<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('home') }}" class="app-brand-link">
            <span class="app-brand-logo demo">
                <img src="{{ asset('logo.png') }}" alt="{{ env('APP_NAME') }}" class="app-brand-logo" width="75">
            </span>
            <span class="app-brand-text demo menu-text fw-bolder ms-2">{{ env('APP_NAME') }}</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-4">
        <!-- Dashboard -->
        <li class="menu-item{{ request()->routeIs('home') ? ' active' : '' }}">
            <a href="{{ route('home') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-alt"></i>
                <div data-i18n="Dashboard">Dashboard</div>
            </a>
        </li>

        <li class="menu-header small text-uppercase"><span class="menu-header-text">Booking Management</span></li>

        {{--        <li class="menu-item{{ request()->routeIs('reports.create') ? ' active' : '' }}"> --}}
        {{--            <a href="{{route('reports.create')}}" class="menu-link"> --}}
        {{--                <i class="menu-icon tf-icons bx bxs-plus-square"></i> --}}
        {{--                <div data-i18n="Book a Workspace">File a Report</div> --}}
        {{--            </a> --}}
        {{--        </li> --}}

        <li class="menu-item{{ request()->routeIs('reports.index') ? ' active' : '' }}">
            <a href="{{ route('reports.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-map"></i>
                <div data-i18n="My Workspaces">View Map</div>
            </a>
        </li>

        <li class="menu-item{{ request()->routeIs('reports.list') ? ' active' : '' }}">
            <a href="{{ route('reports.list') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-shield-alt-2"></i>
                <div data-i18n="My Workspaces">Crime Reports</div>
            </a>
        </li>

        <li class="menu-header small text-uppercase"><span class="menu-header-text">Moderation</span></li>


        <li class="menu-item {{ request()->routeIs('announcements.*') ? ' active' : '' }}">
            <a href="{{ route('announcements.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-message"></i>
                <div data-i18n="My Workspaces">Announcements</div>
            </a>
        </li>

        <li class="menu-header small text-uppercase"><span class="menu-header-text">User Management</span></li>

        <li class="menu-item {{ request()->routeIs('approval.*') ? ' active' : '' }}">
            <a href="{{ route('approval.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user"></i>
                <div data-i18n="My Workspaces">Manage Approvals</div>
            </a>
        </li>

        <li class="menu-item" {{ request()->routeIs('roles.index') ? ' active' : '' }}>
            <a href="{{ route('roles.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-cog"></i>
                <div data-i18n="My Workspaces">Role Administration</div>
            </a>
        </li>
    </ul>
</aside>
