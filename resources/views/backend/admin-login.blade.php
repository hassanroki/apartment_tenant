<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login | Apartment Management</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(
                rgba(0, 0, 0, 0.55),
                rgba(0, 0, 0, 0.55)
            ),

        }

        .login-card {
            border-radius: 18px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        }

        .login-title {
            font-weight: 700;
            letter-spacing: 0.5px;
        }
    </style>
</head>
<body>

<div class="container d-flex align-items-center justify-content-center min-vh-100">
    <div class="col-md-5 col-lg-4">
        <div class="card login-card p-4">
            <div class="text-center mb-4">
                <h3 class="login-title">🏢 Admin Login</h3>
                <p class="text-muted mb-0">Apartment & Tenant Management</p>
            </div>


                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" class="form-control form-control-lg" placeholder="admin@example.com" id="EmailID">
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-control form-control-lg" placeholder="••••••••" id="PasswordID">
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox">
                        <label class="form-check-label">Remember me</label>
                    </div>
                    <a href="#" class="text-decoration-none small">Forgot Password?</a>
                </div>

                <button class="btn btn-primary btn-lg w-100"  onclick="adminLogin()">
                    Login
                </button>


            <div class="text-center mt-4 text-muted small">
                © 2026 Apartment Management System
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>

    async function adminLogin(){
        let EmailValue = document.getElementById('EmailID').value;
        let PasswordValue = document.getElementById('PasswordID').value;

        let obj = {
            "email": EmailValue,
            "password": PasswordValue
        }

        console.log(obj);
        try{
            let URL = 'http://127.0.0.1:8081/api/v1/admin/login';

            let response = await axios.post(URL, obj);
            // Store token in localStorage
            console.log(response);
            localStorage.setItem('token', response.data['access_token']);
            window.location = "/admin/dashboard";
        }catch (error){
            alert('Login failed. Please check your credentials.');
            console.error(error);
        }

    }

</script>

</body>
</html>
