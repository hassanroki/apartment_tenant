<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apartment Booking</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(rgba(245, 247, 250, 0.85), rgba(245, 247, 250, 0.85)),
                url('https://images.unsplash.com/photo-1502672260266-1c1ef2d93688');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        .price {
            color: #0d6efd;
            font-weight: 600;
            font-size: 1.2rem;
        }
    </style>

</head>

<body>
    <div class="container py-5">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm mb-4">
            <div class="container">
                <a class="navbar-brand fw-bold" href="#">Apartment Booking</a>
                <div class="d-flex ms-auto">
                    <button id="loginBtn" class="btn btn-outline-primary me-2">Login</button>
                    <button id="dashboardBtn" class="btn btn-warning">Dashboard</button>
                </div>
            </div>
        </nav>
        <!-- page title -->
        <div class="text-center mb-5">
            <h2 class="fw-bold">Available Apartments</h2>
            <p class="text-muted">Choose your apartment & book easily</p>
        </div>

        <div class="row g-4" id="apartments">
            <!-- apartment card -->

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script>
        // Show/hide login & dashboard button based on token  আগামি ক্লাসে দেখাব
        function toggleAuthButtons() {
            const token = localStorage.getItem('tenant_token');
            if (token) {
                document.getElementById('loginBtn').style.display = 'none';
                document.getElementById('dashboardBtn').style.display = 'inline-block';
            } else {
                document.getElementById('loginBtn').style.display = 'inline-block';
                document.getElementById('dashboardBtn').style.display = 'none';
            }
        }

        // dashboard click
        document.getElementById('dashboardBtn').addEventListener('click', () => {
            window.location = '/tenant/dashboard'; // redirect to login page
        });

        // Login click
        document.getElementById('loginBtn').addEventListener('click', () => {
            window.location = '/tenant/login';
        });

        toggleAuthButtons(); // initial check
        // Show/hide login & dashboard button based on token  আগামি ক্লাসে দেখাব



        // Get Apartment Data
        data()
        async function data() {
            try {
                let response = await axios.get('/api/v1/apartment-data');
                // console.log(response);

                let apartmentsData = response.data.data
                apartmentsData.forEach((item) => {

                    let statusBadge = '';
                    let isDisabled = '';
                    let buttonText = 'Book Now';

                    if (item.status === 0) {
                        statusBadge = `<span class="badge bg-success">Available</span>`;
                    } else if (item.status === 1) {
                        statusBadge = `<span class="badge bg-danger">Booked</span>`;
                        isDisabled = 'disabled';
                        buttonText = 'Already Booked';
                    }

                    document.getElementById('apartments').innerHTML += (`
                    <div class="col-md-6 col-lg-4">
                        <div class="card">
                            <img src="${item['img']}" alt="${item['name']}" class="card-img-top">
                            <div class="card-body">
                                <h2>${item['name']}</h2>
                                <p> <i class="bi bi-geo-alt"></i> Dhanmondi , Dhaka</p>
                                <div class="d-flex justify-content-between align-item-center mb-3">
                                    <span class="price">৳ ${item['rent']} Month</span>
                                     <span>${statusBadge}</span>
                                </div>

                                <!-- date range -->
                                <div class="mb-3">
                                    <label class="form-label small fw-semibold">
                                        Booking Date
                                    </label>
                                    <div class="row g-2">
                                        <div class="col">
                                            <input type="date" class="form-control form-control-sm start-date" ${isDisabled}>
                                        </div>
                                        <div class="col">
                                            <input type="date" class="form-control form-control-sm end-date" ${isDisabled}>
                                        </div>
                                    </div>
                                </div>

                                <!-- booking button -->

                                <button class="btn btn-primary w-100 rounded-pill book-btn" ${isDisabled}
                                    onclick="handleBooking(this, ${item.id})"
                                >
                                    <i class="bi bi-calendar-check"></i> ${buttonText}
                                </button>



                            </div>
                        </div>
                    </div>
                `)
                })
            } catch (error) {
                if (error.response && error.response.status === 401) {
                    localStorage.removeItem('tenant_token');
                    window.location = "/tenant/login";
                } else {
                    alert('Failed to load tasks.');
                    console.log(error);
                }
            }


        }


        // Handle Booking
        async function handleBooking(button, apartmentId) {

            const token = localStorage.getItem('tenant_token');

            if (!token) {
                alert('Please login first to book an apartment.');
                window.location = '/tenant/login';
                return;
            }

            const card = button.closest('.card');
            const startDate = card.querySelector('.start-date').value;
            const endDate = card.querySelector('.end-date').value;

            if (!startDate || !endDate) {
                alert('Please select booking date range.');
                return;
            }

            try {
                const res = await axios.post(
                    '/api/v1/admin/dashboard/bookings', {
                        apartment_id: apartmentId,
                        start_date: startDate,
                        end_date: endDate
                    }, {
                        headers: {
                            Authorization: 'Bearer ' + token
                        }
                    }
                );

                alert('Apartment booked successfully!');
                button.disabled = true;
                button.innerHTML = 'Booked';

            } catch (err) {

                if (err.response && err.response.status === 401) {
                    localStorage.removeItem('tenant_token');
                    window.location = '/tenant/login';
                } else {
                    alert(err.response?.data?.message || 'Booking failed!');
                    console.error(err);
                }
            }
        }
    </script>
</body>

</html>
