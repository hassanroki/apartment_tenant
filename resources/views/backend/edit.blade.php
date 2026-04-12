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
                <a class="nav-link active">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="bi bi-building me-2"></i> Apartments
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="bi bi-people me-2"></i> Tenants
                </a>
            </li>

            <li class="nav-item mt-3">
                <a class="nav-link text-danger" href="#">
                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                </a>
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
                            <i class="bi bi-building card-icon text-primary me-3"></i>
                            <div>
                                <h6 class="mb-0">Total Apartments</h6>
                                <h4 class="fw-bold" id="apartment">0</h4>
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
                                <h4 class="fw-bold">35</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Apartment CRUD Section -->
            <div class="container py-5">

                <!-- Page Header -->
                <div class="page-header p-4 mb-4 shadow-sm">
                    <h3 class="mb-1">🏢 Apartment Add / Edit</h3>
                    <p class="mb-0 small">Apartment Management System</p>
                </div>

                <!-- Form Card -->
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">

                        <form id="apartmentForm" enctype="multipart/form-data" onsubmit="updateApartment(event)">
                            <input type="hidden" name="apartment_id" id="apartment_id">

                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Apartment Name</label>
                                    <input type="text" name="name" id="name" class="form-control" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Monthly Rent</label>
                                    <input type="number" name="rent" id="rent" class="form-control" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Status</label>
                                    <select name="status" id="status" class="form-select">
                                        <option value="0">Available</option>
                                        <option value="1">Booked</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Apartment Image</label>
                                    <input type="file" name="img" id="img" class="form-control">
                                    <img id="imagePreview" src="" alt="Apartment Image" class="mt-2"
                                        style="max-width: 150px; display: none;">
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-semibold">Description</label>
                                    <textarea name="descriptions" id="descriptions" rows="4" class="form-control"></textarea>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">
                                    Save Apartment
                                </button>
                            </div>
                        </form>


                    </div>
                </div>

            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        // Get Apartment Data
        fillApartment()
        async function fillApartment() {
            let url = window.location.pathname; // /admin/dashboard/apartment/5/edit
            let parts = url.split('/');
            let apartmentId = parts[4]; // 0:'',1:'admin',2:'dashboard',3:'apartment',4:'5',5:'edit'

            let token = localStorage.getItem('token');
            if (!token) {
                window.location = "/admin/login";
                return;
            }

            try {
                let response = await axios.get(`http://127.0.0.1:8000/api/v1/admin/dashboard/apartment/${apartmentId}/edit`, {
                    headers: {
                        'Authorization': 'Bearer ' + token
                    }
                });
                console.log(response);

                let apartment = response.data.data; // assuming API returns { data: {...} }

                // Prefill form
                document.getElementById('apartment_id').value = apartment.id;
                document.getElementById('name').value = apartment.name;
                document.getElementById('rent').value = apartment.rent;
                document.getElementById('status').value = apartment.status;
                document.getElementById('descriptions').value = apartment.descriptions;


                // Old image preview
                let preview = document.getElementById('imagePreview');
                if (apartment.img) {
                    preview.src = `/storage/${apartment.img}`;
                    preview.style.display = 'block';
                } else {
                    preview.style.display = 'none';
                }

                // Live preview: new image select করলে old image replace হবে
                document.getElementById('img').addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    const preview = document.getElementById('imagePreview');

                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(event) {
                            preview.src = event.target.result; // new image show
                            preview.style.display = 'block';
                        }
                        reader.readAsDataURL(file);
                    } else {
                        // যদি user file remove করে
                        preview.src = '';
                        preview.style.display = 'none';
                    }
                });

            } catch (error) {
                alert(error.message || 'Error ❌');
            }
        }


        // Update Apartment
        async function updateApartment(event) {
            event.preventDefault();

            let token = localStorage.getItem('token');
            if (!token) {
                window.location = "/admin/login";
                return;
            }

            let form = document.getElementById('apartmentForm');
            let formData = new FormData(form);

            let apartmentId = document.getElementById('apartment_id').value;
            if (!apartmentId) {
                alert("Apartment ID missing!");
                return;
            }

            // Laravel uses PUT for update
            formData.append('_method', 'PUT');

            try {
                let response = await axios.post(`/api/v1/admin/dashboard/apartments/${apartmentId}`, formData, {
                    headers: {
                        'Authorization': 'Bearer ' + token,
                        'Content-Type': 'multipart/form-data'
                    }
                });

                alert('Apartment updated successfully ✅');
                window.location = "/admin/dashboard";

            } catch (error) {
                console.error(error);
                alert(error.response?.data?.message || 'Update failed ❌');
            }
        }
    </script>


</body>

</html>
