@extends('backend.layouts.main')

@section('title', 'Tenant List')

@section('content')
    <div class="container-fluid py-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="mb-1">Tenant Management</h3>
                <p class="text-muted mb-0">Manage and view all tenants</p>
            </div>

            <a href="/admin/dashboard/apartment/create" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Add Apartment
            </a>
        </div>

        {{-- Statistics --}}
        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="card shadow-sm border-0">
                    <div class="card-body d-flex align-items-center">
                        <i class="bi bi-building card-icon text-primary me-3 fs-1"></i>
                        <div>
                            <h6 class="text-muted mb-1">Total Apartments</h6>
                            <h3 class="fw-bold mb-0" id="totalApartment">0</h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow-sm border-0">
                    <div class="card-body d-flex align-items-center">
                        <i class="bi bi-people card-icon text-success me-3 fs-1"></i>
                        <div>
                            <h6 class="text-muted mb-1">Total Tenants</h6>
                            <h3 class="fw-bold mb-0" id="totalTenant">0</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tenant List --}}
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h5 class="mb-0">Tenant List</h5>
                <button class="btn btn-outline-primary btn-sm" onclick="getTenants()">
                    <i class="bi bi-arrow-clockwise me-1"></i> Refresh
                </button>
            </div>

            <div class="card-body">
                <div id="tenantAlert" class="alert d-none" role="alert"></div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 80px;">#</th>
                                <th>Tenant Name</th>
                                <th>Mobile</th>
                            </tr>
                        </thead>

                        <tbody id="tenant-data">
                            <tr>
                                <td colspan="3" class="text-center py-4">
                                    Loading tenants...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const token = localStorage.getItem('token');

        const tenantTable = document.getElementById('tenant-data');
        const tenantAlert = document.getElementById('tenantAlert');

        function showTenantAlert(message, type = 'danger') {
            tenantAlert.className = `alert alert-${type}`;
            tenantAlert.textContent = message;
            tenantAlert.classList.remove('d-none');
        }

        function hideTenantAlert() {
            tenantAlert.classList.add('d-none');
            tenantAlert.textContent = '';
        }

        function normalizeList(responseData) {
            let data = responseData?.data ?? responseData;

            // Supports a nested data array if returned by the API.
            if (data && !Array.isArray(data) && Array.isArray(data.data)) {
                data = data.data;
            }

            if (!Array.isArray(data)) {
                return [];
            }

            return data.flat(Infinity);
        }

        async function getTenants() {
            hideTenantAlert();

            if (!token) {
                window.location.href = '/admin/login';
                return;
            }

            tenantTable.innerHTML = `
            <tr>
                <td colspan="3" class="text-center py-4">
                    Loading tenants...
                </td>
            </tr>
        `;

            try {
                const response = await axios.get(
                    '/api/v1/admin/dashboard/tenants', {
                        headers: {
                            Authorization: `Bearer ${token}`,
                            Accept: 'application/json'
                        }
                    }
                );

                const tenants = normalizeList(response.data);

                document.getElementById('totalTenant').textContent = tenants.length;

                if (tenants.length === 0) {
                    tenantTable.innerHTML = `
                    <tr>
                        <td colspan="3" class="text-center text-muted py-4">
                            No tenants found.
                        </td>
                    </tr>
                `;
                    return;
                }

                tenantTable.replaceChildren();

                tenants.forEach((item, index) => {
                    const tr = document.createElement('tr');

                    const numberTd = document.createElement('td');
                    numberTd.textContent = index + 1;

                    const nameTd = document.createElement('td');
                    nameTd.textContent = item.tenantName ?? 'N/A';

                    const phoneTd = document.createElement('td');
                    phoneTd.textContent = item.phoneNumber ?? 'N/A';

                    tr.append(numberTd, nameTd, phoneTd);
                    tenantTable.appendChild(tr);
                });

            } catch (error) {
                if (error.response?.status === 401) {
                    localStorage.removeItem('token');
                    window.location.href = '/admin/login';
                    return;
                }

                tenantTable.innerHTML = `
                <tr>
                    <td colspan="3" class="text-center text-danger py-4">
                        Failed to load tenants.
                    </td>
                </tr>
            `;

                showTenantAlert(
                    error.response?.data?.message || 'Failed to load tenants.'
                );

                console.error('Tenant API error:', error);
            }
        }

        async function getApartmentCount() {
            if (!token) {
                return;
            }

            try {
                const response = await axios.get(
                    '/api/v1/admin/dashboard/data', {
                        headers: {
                            Authorization: `Bearer ${token}`,
                            Accept: 'application/json'
                        }
                    }
                );

                const result = response.data?.data ?? response.data;

                // Adjust these keys if your API returns a different structure.
                const apartments = Array.isArray(result) ?
                    result :
                    result?.apartments;

                document.getElementById('totalApartment').textContent =
                    Array.isArray(apartments) ?
                    apartments.length :
                    (result?.totalApartment ?? result?.total_apartment ?? 0);

            } catch (error) {
                if (error.response?.status === 401) {
                    localStorage.removeItem('token');
                    window.location.href = '/admin/login';
                    return;
                }

                console.error('Apartment count API error:', error);
            }
        }

        async function logOut() {
            const currentToken = localStorage.getItem('token');

            if (!currentToken) {
                window.location.href = '/admin/login';
                return;
            }

            try {
                await axios.post(
                    '/api/v1/admin/dashboard/logout', {}, {
                        headers: {
                            Authorization: `Bearer ${currentToken}`,
                            Accept: 'application/json'
                        }
                    }
                );
            } catch (error) {
                console.error('Logout API error:', error);
            } finally {
                localStorage.removeItem('token');
                window.location.href = '/admin/login';
            }
        }

        getTenants();
        getApartmentCount();
    </script>
@endpush
