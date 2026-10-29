@extends('backend.layouts.main')

@section('title', 'Apartments | Apartment Management')
@section('page-heading', 'Apartments')

@section('content')

    <div class="card shadow-sm border-0">

        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Apartment List</h5>

            <a class="btn btn-primary btn-sm" href="/admin/dashboard/apartment/create">
                <i class="bi bi-plus-circle"></i>
                Add Apartment
            </a>
        </div>

        <div class="card-body">

            <div class="table-responsive">
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
                        <tr>
                            <td colspan="6" class="text-center">
                                Loading apartments...
                            </td>
                        </tr>
                    </tbody>

                </table>
            </div>

        </div>
    </div>

@endsection

@push('scripts')
    <script>
        async function getData() {
            const token = localStorage.getItem('token');
            const tbody = document.getElementById('apartment-data');

            if (!token) {
                window.location.href = '/admin/login';
                return;
            }

            try {
                const response = await axios.get(
                    '/api/v1/admin/dashboard/data', {
                        headers: {
                            Authorization: 'Bearer ' + token
                        }
                    }
                );

                const data = response.data.data || [];

                if (data.length === 0) {
                    tbody.innerHTML = `
                    <tr>
                        <td colspan="6" class="text-center">
                            No apartments found.
                        </td>
                    </tr>
                `;
                    return;
                }

                tbody.innerHTML = data.map((item, index) => {
                    const statusBadge = Number(item.status) === 0 ?
                        '<span class="badge bg-success">Available</span>' :
                        '<span class="badge bg-danger">Booked</span>';

                    const imageHtml = item.img ?
                        `<img src="/storage/${encodeURIComponent(item.img).replace(/%2F/g, '/')}"
                            alt="Apartment image"
                            style="max-width:80px;height:auto;border-radius:4px;">` :
                        '<span class="text-muted">No image</span>';

                    return `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${escapeHtml(item.name ?? '')}</td>
                        <td>৳ ${escapeHtml(String(item.rent ?? 0))}</td>
                        <td>${imageHtml}</td>
                        <td>${statusBadge}</td>
                        <td>
                            <a class="btn btn-sm btn-warning"
                               href="/admin/dashboard/apartment/${item.id}/edit"
                               title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>

                            <button type="button"
                                    class="btn btn-sm btn-danger"
                                    onclick="deleteApartment(${Number(item.id)})"
                                    title="Delete">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
                }).join('');

            } catch (error) {
                if (error.response?.status === 401) {
                    localStorage.removeItem('token');
                    window.location.href = '/admin/login';
                    return;
                }

                console.error('Apartment loading error:', error);

                tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center text-danger">
                        Failed to load apartments.
                    </td>
                </tr>
            `;
            }
        }

        function escapeHtml(value) {
            return String(value).replace(/[&<>"']/g, character => ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            })[character]);
        }

        async function deleteApartment(apartmentId) {
            if (!confirm('Are you sure you want to delete this apartment?')) {
                return;
            }

            const token = localStorage.getItem('token');

            if (!token) {
                window.location.href = '/admin/login';
                return;
            }

            try {
                await axios.delete(
                    `/api/v1/admin/dashboard/apartments/${apartmentId}`, {
                        headers: {
                            Authorization: 'Bearer ' + token
                        }
                    }
                );

                alert('Apartment deleted successfully!');
                await getData();

            } catch (error) {
                if (error.response?.status === 401) {
                    localStorage.removeItem('token');
                    window.location.href = '/admin/login';
                    return;
                }

                alert(
                    error.response?.data?.message ||
                    'Failed to delete apartment.'
                );

                console.error('Apartment deletion error:', error);
            }
        }

        getData();
    </script>
@endpush
