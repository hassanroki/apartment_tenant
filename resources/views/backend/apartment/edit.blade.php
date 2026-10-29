
@extends('backend.layouts.main')

@section('title', 'Edit Apartment')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between mb-4">
        <h3>Edit Apartment</h3>
        <a href="/admin/dashboard/apartment" class="btn btn-secondary">Back</a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form id="apartmentForm" onsubmit="updateApartment(event)">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Apartment Name</label>
                    <input type="text" id="name" name="name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Rent</label>
                    <input type="number" id="rent" name="rent" class="form-control" min="0" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select id="status" name="status" class="form-select" required>
                        <option value="1">Available</option>
                        <option value="0">Occupied</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Apartment Image</label>
                    <input type="file" id="img" name="img" class="form-control" accept="image/*">

                    <img id="imagePreview" src="" class="mt-3 rounded d-none"
                         style="width:180px;height:120px;object-fit:cover;" alt="Apartment image">
                </div>

                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea id="descriptions" name="descriptions" class="form-control" rows="4"></textarea>
                </div>

                <button type="submit" id="updateBtn" class="btn btn-primary">
                    Update Apartment
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const token = localStorage.getItem('token');
const parts = location.pathname.split('/').filter(Boolean);
const index = parts.indexOf('apartment');
const apartmentId = index >= 0 ? parts[index + 1] : null;

const preview = document.getElementById('imagePreview');

if (!token) {
    location.href = '/admin/login';
}

async function fillApartment() {
    if (!token || !apartmentId || isNaN(Number(apartmentId))) {
        alert('Invalid apartment ID or login required.');
        return;
    }

    try {
        const response = await axios.get(
            `/api/v1/admin/dashboard/apartment/${apartmentId}/edit`,
            { headers: { Authorization: `Bearer ${token}` } }
        );

        const apartment = response.data.data;

        document.getElementById('name').value = apartment.name ?? '';
        document.getElementById('rent').value = apartment.rent ?? '';

        // Status must be 1 (true) or 0 (false).
        document.getElementById('status').value =
            Number(apartment.status) === 1 ? '1' : '0';

        document.getElementById('descriptions').value =
            apartment.descriptions ?? '';

        if (apartment.img) {
            preview.src = apartment.img.startsWith('http') ||
                          apartment.img.startsWith('/')
                ? apartment.img
                : `/storage/${apartment.img}`;

            preview.classList.remove('d-none');
        }
    } catch (error) {
        alert(error.response?.data?.message || 'Failed to load apartment.');
    }
}

document.getElementById('img').addEventListener('change', function () {
    const file = this.files[0];

    if (file) {
        preview.src = URL.createObjectURL(file);
        preview.classList.remove('d-none');
    }
});

async function updateApartment(event) {
    event.preventDefault();

    const button = document.getElementById('updateBtn');
    const formData = new FormData(document.getElementById('apartmentForm'));

    // Ensure status is sent as 1 or 0.
    formData.set('status', document.getElementById('status').value);
    formData.append('_method', 'PUT');

    button.disabled = true;

    try {
        await axios.post(
            `/api/v1/admin/dashboard/apartments/${apartmentId}`,
            formData,
            {
                headers: {
                    Authorization: `Bearer ${token}`,
                    Accept: 'application/json'
                }
            }
        );

        alert('Apartment updated successfully!');
        location.href = '/admin/dashboard/apartment';
    } catch (error) {
        if (error.response?.status === 401) {
            localStorage.removeItem('token');
            location.href = '/admin/login';
            return;
        }

        alert(error.response?.data?.message || 'Failed to update apartment.');
    } finally {
        button.disabled = false;
    }
}

fillApartment();
</script>
@endpush
