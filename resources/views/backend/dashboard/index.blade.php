@extends('backend.layouts.main')

@section('title', 'Dashboard | Apartment Management')
@section('page-heading', 'Dashboard')

@section('content')

    <!-- Statistics -->
    <div class="row g-4">

        <!-- Total Apartments -->
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-body d-flex align-items-center">
                    <i class="bi bi-building card-icon text-success me-3"></i>

                    <div>
                        <h6 class="mb-1">Total Apartments</h6>
                        <h3 class="fw-bold mb-0" id="apartmentCount">0</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Tenants -->
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-body d-flex align-items-center">
                    <i class="bi bi-people card-icon text-primary me-3"></i>

                    <div>
                        <h6 class="mb-1">Total Tenants</h6>
                        <h3 class="fw-bold mb-0" id="totalTenant">0</h3>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection

@push('scripts')
    <script>
        async function loadDashboard() {
            const token = localStorage.getItem('token');

            if (!token) {
                window.location.href = '/admin/login';
                return;
            }

            const headers = {
                Authorization: 'Bearer ' + token
            };

            try {
                const [apartmentResponse, tenantResponse] = await Promise.all([
                    axios.get('/api/v1/admin/dashboard/data', {
                        headers
                    }),
                    axios.get('/api/v1/admin/dashboard/tenants', {
                        headers
                    })
                ]);

                const apartments = apartmentResponse.data.data || [];
                const tenants = tenantResponse.data.data || [];

                document.getElementById('apartmentCount').textContent =
                    apartments.length;

                document.getElementById('totalTenant').textContent =
                    tenants.flat().length;

            } catch (error) {
                if (error.response?.status === 401) {
                    localStorage.removeItem('token');
                    window.location.href = '/admin/login';
                    return;
                }

                console.error('Dashboard loading error:', error);
            }
        }

        loadDashboard();
    </script>
@endpush
