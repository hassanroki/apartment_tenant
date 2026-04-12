<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Tenant Registration | Apartment Management</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(rgba(0, 0, 0, .55), rgba(0, 0, 0, .55)),
                url('https://images.unsplash.com/photo-1501183638710-841dd1904471') no-repeat center/cover;
        }

        .login-card {
            border-radius: 18px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }

        .login-title {
            font-weight: 700;
        }
    </style>
</head>

<body>

    <div class="container d-flex align-items-center justify-content-center min-vh-100">
        <div class="col-md-5 col-lg-4">
            <div class="card login-card p-4">

                <div class="text-center mb-4">
                    <h3 class="login-title">🏢 Tenant Registration</h3>
                    <p class="text-muted mb-0">Apartment & Tenant Management</p>
                </div>

                <form onsubmit="tenantRegistration(event)">

                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input id="name" type="text" class="form-control form-control-lg"
                            placeholder="Mr. Rahman" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input id="phone" type="text" class="form-control form-control-lg"
                            placeholder="01515....." required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input id="password" type="password" class="form-control form-control-lg" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input id="confirm_password" type="password" class="form-control form-control-lg" required>
                        <small id="passwordError" class="text-danger d-none">
                            Password does not match
                        </small>
                    </div>

                    <button class="btn btn-primary btn-lg w-100" type="submit">
                        Register
                    </button>

                    <div class="text-center mt-3">
                        <a href="/tenant/login" class="text-decoration-none small">
                            Already have an account? Login
                        </a>
                    </div>

                </form>

                <div class="text-center mt-4 text-muted small">
                    © 2026 Apartment Management System
                </div>

            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        async function tenantRegistration(e) {
            e.preventDefault();

            let name = document.getElementById('name').value;
            let phone = document.getElementById('phone').value;
            let password = document.getElementById('password').value;
            let confirmPassword = document.getElementById('confirm_password').value;
            let error = document.getElementById('passwordError');

            if (password !== confirmPassword) {
                error.classList.remove('d-none');
                return;
            } else {
                error.classList.add('d-none');
            }

            let data = {
                name: name,
                phone: phone,
                password: password,
                password_confirmation: confirmPassword
            };

            try {
                await axios.post('/api/v1/register', data);
                alert("Registration successfully?");
                window.location.href = '/tenant/login';
            } catch (err) {
                alert('Registration failed!');
                console.error(err.message);
            }
        }
    </script>

</body>

</html>
