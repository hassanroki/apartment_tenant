@extends('backend.layouts.main')

@section('title', 'Add Apartment | Apartment Management')
@section('page-heading', 'Apartment Management')

@push('styles')
    <style>
        .page-header {
            background: #fff;
            border-radius: 10px;
        }

        #imagePreview {
            max-width: 200px;
            max-height: 200px;
            display: none;
            border-radius: 8px;
            object-fit: cover;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.15);
        }
    </style>
@endpush

@section('content')

    <div class="container-fluid py-3">

        <!-- Page Header -->
        <div class="page-header p-4 mb-4 shadow-sm">
            <h3 class="mb-1">🏢 Apartment Add / Edit</h3>
            <p class="mb-0 small text-muted">Apartment Management System</p>
        </div>

        <!-- Form Card -->
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">

                <form id="apartmentForm" enctype="multipart/form-data" onsubmit="saveApartment(event)">

                    <input type="hidden" name="apartment_id" id="apartment_id">

                    <div class="row g-4">

                        <!-- Apartment Name -->
                        <div class="col-md-6">
                            <label for="name" class="form-label fw-semibold">
                                Apartment Name
                            </label>

                            <input type="text" name="name" id="name" class="form-control"
                                placeholder="Enter apartment name" required>
                        </div>

                        <!-- Monthly Rent -->
                        <div class="col-md-6">
                            <label for="rent" class="form-label fw-semibold">
                                Monthly Rent
                            </label>

                            <input type="number" name="rent" id="rent" class="form-control"
                                placeholder="Enter monthly rent" min="0" step="0.01" required>
                        </div>

                        <!-- Status -->
                        <div class="col-md-6">
                            <label for="status" class="form-label fw-semibold">
                                Status
                            </label>

                            <select name="status" id="status" class="form-select" required>
                                <option value="0">Available</option>
                                <option value="1">Booked</option>
                            </select>
                        </div>

                        <!-- Apartment Image -->
                        <div class="col-md-6">
                            <label for="img" class="form-label fw-semibold">
                                Apartment Image
                            </label>

                            <input type="file" name="img" id="img" class="form-control"
                                accept=".jpg,.jpeg,.png,.gif,.webp">

                            <div class="mt-3">
                                <img id="imagePreview" src="" alt="Apartment Image Preview">
                            </div>

                            <small class="text-muted">
                                Allowed types: jpg, jpeg, png, gif, webp. Max size: 2MB.
                            </small>
                        </div>

                        <!-- Description -->
                        <div class="col-12">
                            <label for="descriptions" class="form-label fw-semibold">
                                Description
                            </label>

                            <textarea name="descriptions" id="descriptions" rows="4" class="form-control"
                                placeholder="Enter apartment description"></textarea>
                        </div>

                    </div>

                    <!-- Buttons -->
                    <div class="mt-4 d-flex gap-2">
                        <button type="submit" id="submitBtn" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i>
                            Save Apartment
                        </button>

                        <a href="/admin/dashboard/apartment" class="btn btn-secondary">
                            Cancel
                        </a>
                    </div>

                </form>

            </div>
        </div>

    </div>

@endsection

@push('scripts')
    <script>
        const apartmentForm = document.getElementById('apartmentForm');
        const imageInput = document.getElementById('img');
        const imagePreview = document.getElementById('imagePreview');
        const submitBtn = document.getElementById('submitBtn');

        // Image Live Preview and Validation
        imageInput.addEventListener('change', function() {
            const file = this.files[0];

            if (!file) {
                imagePreview.src = '';
                imagePreview.style.display = 'none';
                return;
            }

            const allowedTypes = [
                'image/jpeg',
                'image/png',
                'image/gif',
                'image/webp'
            ];

            if (!allowedTypes.includes(file.type)) {
                alert('Please select a JPG, JPEG, PNG, GIF, or WEBP image.');
                this.value = '';
                imagePreview.src = '';
                imagePreview.style.display = 'none';
                return;
            }

            if (file.size > 2 * 1024 * 1024) {
                alert('Image size must not exceed 2MB.');
                this.value = '';
                imagePreview.src = '';
                imagePreview.style.display = 'none';
                return;
            }

            const reader = new FileReader();

            reader.onload = function(event) {
                imagePreview.src = event.target.result;
                imagePreview.style.display = 'block';
            };

            reader.readAsDataURL(file);
        });

        // Save Apartment
        async function saveApartment(event) {
            event.preventDefault();

            const token = localStorage.getItem('token');

            if (!token) {
                window.location.href = '/admin/login';
                return;
            }

            const imageFile = imageInput.files[0];

            // Client-side image validation
            if (imageFile) {
                const allowedTypes = [
                    'image/jpeg',
                    'image/png',
                    'image/gif',
                    'image/webp'
                ];

                if (!allowedTypes.includes(imageFile.type) ||
                    imageFile.size > 2 * 1024 * 1024) {
                    alert('Please select a valid image up to 2MB.');
                    return;
                }
            }

            const formData = new FormData(apartmentForm);
            const apartmentId = document.getElementById('apartment_id').value;

            // Default: Create Apartment
            let url = '/api/v1/admin/dashboard/apartments';
            let method = 'post';

            // Update mode when an apartment ID is provided
            if (apartmentId) {
                url += '/' + encodeURIComponent(apartmentId);

                // Laravel method spoofing for multipart form data
                formData.append('_method', 'PUT');
            }

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Saving...';

            try {
                await axios.post(url, formData, {
                    headers: {
                        Authorization: 'Bearer ' + token
                    }
                });

                alert(apartmentId ?
                    'Apartment updated successfully!' :
                    'Apartment added successfully!');

                window.location.href = '/admin/dashboard/apartment';

            } catch (error) {
                if (error.response?.status === 401) {
                    localStorage.removeItem('token');
                    window.location.href = '/admin/login';
                    return;
                }

                const responseData = error.response?.data;

                if (responseData?.errors) {
                    const messages = Object.values(responseData.errors).flat();
                    alert(messages.join('\n'));
                } else {
                    alert(
                        responseData?.message ||
                        error.message ||
                        'Failed to save apartment.'
                    );
                }

                console.error('Save apartment error:', error);

            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="bi bi-save me-1"></i> Save Apartment';
            }
        }
    </script>
@endpush
