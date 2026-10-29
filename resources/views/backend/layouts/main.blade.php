<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Admin Dashboard | Apartment Management')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background-color: #f4f6f9;
        }

        .sidebar {
            width: 250px;
            min-height: 100vh;
            background: #1e293b;
            top: 0;
            left: 0;
            z-index: 1000;
        }

        .sidebar a {
            color: #cbd5e1;
            text-decoration: none;
        }

        .sidebar a:hover,
        .sidebar .active {
            background: #334155;
            color: #fff;
        }

        .sidebar .nav-link {
            border-radius: 6px;
        }

        .content {
            margin-left: 250px;
            min-height: 100vh;
        }

        .card-icon {
            font-size: 2rem;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 200px;
            }

            .content {
                margin-left: 200px;
            }
        }
    </style>

    @stack('styles')
</head>

<body>

    <!-- Sidebar -->
    @include('backend.layouts.sidebar')

    <!-- Main Content -->
    <div class="content">

        <!-- Top Navbar -->
        <nav class="navbar navbar-light bg-white shadow-sm px-4">
            <a href="/admin/dashboard" class="navbar-brand fw-semibold">
                @yield('page-heading', 'Dashboard')
            </a>

            <span class="text-muted">Admin</span>
        </nav>

        <!-- Page Content -->
        <div class="container-fluid p-4">
            @yield('content')
        </div>

    </div>

    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script>
        // Logout
        async function logOut() {
            const token = localStorage.getItem('token');

            if (!token) {
                window.location.href = '/admin/login';
                return;
            }

            try {
                await axios.post('/api/v1/admin/dashboard/logout', {}, {
                    headers: {
                        Authorization: 'Bearer ' + token
                    }
                });
            } catch (error) {
                console.error('Logout error:', error);
            } finally {
                localStorage.removeItem('token');
                window.location.href = '/admin/login';
            }
        }
    </script>

    @stack('scripts')

</body>

</html>
