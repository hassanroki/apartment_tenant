<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard | Apartment Management</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background-color: #f4f6f9;
        }

        .sidebar {
            width: 250px;
            min-height: 100vh;
            background: #1e293b;
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

        .content {
            margin-left: 250px;
        }

        .card-icon {
            font-size: 2rem;
        }
    </style>
</head>

<body>

    <!-- Sidebar -->
    <div class="sidebar position-fixed p-3">
        <h4 class="text-white mb-4">🏢 Admin Panel</h4>

        <ul class="nav nav-pills flex-column gap-1">
            <li class="nav-item">
                <a class="nav-link active" href="/admin/dashboard">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="/admin/dashboard/tenant-list">
                    <i class="bi bi-people me-2"></i> Tenants
                </a>
            </li>

            <li class="nav-item mt-3">
                <button class="nav-link text-danger" onclick="logOut()">
                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                </button>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="content">

        <!-- Top Navbar -->
        <nav class="navbar navbar-light bg-white shadow-sm px-4">
            <span class="navbar-brand fw-semibold">Dashboard</span>
            <span class="text-muted">Admin</span>
        </nav>

        <div class="container-fluid p-4">

            <!-- Stats -->
            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body d-flex align-items-center">
                            <i class="bi bi-people card-icon text-success me-3"></i>
                            <div>
                                <h6 class="mb-0">Total Apartment</h6>
                                <h3 id="apartmet">0</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body d-flex align-items-center">
                            <i class="bi bi-people card-icon text-success me-3"></i>
                            <div>
                                <h6 class="mb-0">Total Tenants</h6>
                                <h4 class="fw-bold" id="totalTenant">0</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Apartment CRUD Section -->
            <div class="card shadow-sm border-0">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Tenant List</h5>
                    <a class="btn btn-primary btn-sm" href="/admin/dashboard/apartment/create">
                        <i class="bi bi-plus-circle"></i> Add Apartment
                    </a>
                </div>

                <div class="card-body">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Mobile</th>
                            </tr>
                        </thead>
                        <tbody id="tenant-data">
                            {{-- comes from api call --}}
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        // Get Tenant Data
        getData();
        async function getData() {
            let token = localStorage.getItem('token');

            if (!token) {
                window.location = "/admin/login";
                return;
            }

            try {
                let URL = '/api/v1/admin/dashboard/tenants';
                let response = await axios.get(URL, {
                    headers: {
                        'Authorization': 'Bearer ' + token
                    }
                });

                // console.log(response);
                let data = response.data.data.flat();
                document.getElementById('totalTenant').innerText = data.length;
                data.forEach((item, index) => {

                    document.getElementById('tenant-data').innerHTML += (`
                <tr>
                    <td>${index + 1}</td>
                    <td>${item['tenantName']}</td>
                    <td>${item['phoneNumber']}</td>

                 </tr>
            `)
                })
            } catch (error) {
                if (error.response && error.response.status === 401) {
                    localStorage.removeItem('token');
                    window.location = "/login";
                } else {
                    alert('Failed to load Tenants.');
                    console.log(error);
                }
            }

        }

        async function logOut() {
            let token = localStorage.getItem('token');

            if (!token) {
                window.location = "/admin/login";
                return;
            }

            try {
                let URL = '/api/v1/admin/dashboard/logout';
                let response = await axios.post(URL, {}, {
                    headers: {
                        'Authorization': 'Bearer ' + token
                    }
                });
                localStorage.removeItem('token');
                window.location = "/admin/login";
            } catch (error) {
                localStorage.removeItem('token');
                window.location = "/admin/login";
            }
        }
    </script>

</body>

</html>
