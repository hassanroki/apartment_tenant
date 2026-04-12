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
                <a href="/admin/dashboard" class="nav-link active">
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
            <a href="/admin/dashboard" class="navbar-brand fw-semibold">
                Dashboard
            </a>
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
                    <h5 class="mb-0">Apartment List</h5>
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
                                <th>Rent</th>
                                <th>Image</th>
                                <th>Status</th>
                                <th width="160">Action</th>
                            </tr>
                        </thead>
                        <tbody id="apartment-data">
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
        // Get All Tenant
        totalTenant()
        async function totalTenant() {
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


            } catch (error) {
                console.log(error.message);
                if (error.response && error.response.status === 401) {
                    localStorage.removeItem('token');
                    window.location = "/admin/login";
                } else {
                    alert('Failed to load total Tenants.');
                    console.log(error);
                }
            }
        }

        // All Apartments
        getData();
        async function getData() {
            let token = localStorage.getItem('token');

            if (!token) {
                window.location = "/admin/login";
                return;
            }

            try {
                let URL = '/api/v1/admin/dashboard/data';
                let response = await axios.get(URL, {
                    headers: {
                        'Authorization': 'Bearer ' + token
                    }
                });

                let data = response.data['data'];
                console.log(data);
                document.getElementById('apartmet').innerText = data.length;
                data.forEach((item, index) => {
                    // Dynamic status badge
                    let statusBadge = '';
                    if (item.status === 0) {
                        statusBadge = `<span class="badge bg-success">Available</span>`;
                    } else if (item.status === 1) {
                        statusBadge = `<span class="badge bg-danger">Booked</span>`;
                    }

                    // Image preview
                    let imageHtml = '';
                    if (item.img) {
                        imageHtml =
                            `<img src="/storage/${item.img}" alt="${item.name}" style="max-width: 80px; height:auto; border-radius:4px;">`;
                    }

                    document.getElementById('apartment-data').innerHTML += (`
                <tr>
                    <td>${index + 1}</td>
                    <td>${item['name']}</td>
                    <td>৳ ${item['rent']}</td>
                    <td>${imageHtml}</td>
                    <td>${statusBadge}</td>
                    <td>
                        <a class="btn btn-sm btn-warning" href="/admin/dashboard/apartment/${item.id}/edit">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <button class="btn btn-sm btn-danger"  onclick="deleteApartment(${item.id})">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>

                 </tr>
            `)
                })
            } catch (error) {
                if (error.response && error.response.status === 401) {
                    localStorage.removeItem('token');
                    window.location = "/login";
                } else {
                    alert('Failed to load Apartments.');
                    console.log(error);
                }
            }

        }


        // Logout
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


        // DELETE Apartment
        async function deleteApartment(apartmentId) {
            if (!confirm('Are you sure you want to delete this apartment?')) return;

            let token = localStorage.getItem('token');
            if (!token) {
                window.location = "/admin/login";
                return;
            }

            try {
                await axios.delete(`/api/v1/admin/dashboard/apartments/${apartmentId}`, {
                    headers: {
                        'Authorization': 'Bearer ' + token
                    }
                });
                alert('Apartment deleted successfully ✅');
                window.location = "/admin/dashboard";
            } catch (error) {
                alert(error.response?.data?.message || 'Failed to delete apartment ❌');
                console.log(error);
            }
        }
    </script>

</body>

</html>
