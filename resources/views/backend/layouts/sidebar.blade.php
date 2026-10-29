<div class="sidebar position-fixed p-3">

    <h4 class="text-white mb-4">🏢 Admin Panel</h4>

    <ul class="nav nav-pills flex-column gap-1">

        <li class="nav-item">
            <a href="/admin/dashboard" class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2 me-2"></i>
                Dashboard
            </a>
        </li>

        <li class="nav-item">
            <a href="/admin/dashboard/apartment"
                class="nav-link {{ request()->is('admin/dashboard/apartment*') ? 'active' : '' }}">
                <i class="bi bi-building me-2"></i>
                Apartments
            </a>
        </li>

        <li class="nav-item">
            <a href="/admin/dashboard/tenant-list"
                class="nav-link {{ request()->is('admin/dashboard/tenant.index*') ? 'active' : '' }}">
                <i class="bi bi-people me-2"></i>
                Tenants
            </a>
        </li>

        <li class="nav-item mt-3">
            <button type="button" class="nav-link text-danger w-100 text-start" onclick="logOut()">
                <i class="bi bi-box-arrow-right me-2"></i>
                Logout
            </button>
        </li>

    </ul>

</div>
