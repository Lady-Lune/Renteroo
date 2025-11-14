@extends('layouts.app')

@section('content')
<style>
    .form-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        padding: 2rem;
        margin-bottom: 2rem;
    }

    .form-section-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #212529;
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #f0f0f0;
    }

    .current-image {
        max-width: 200px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .image-upload-area {
        border: 3px dashed #e9ecef;
        border-radius: 15px;
        padding: 2rem;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
        background: #f8f9fa;
    }

    .image-upload-area:hover {
        border-color: #667eea;
        background: rgba(102, 126, 234, 0.05);
    }

    .status-option {
        padding: 1rem;
        border: 2px solid #e9ecef;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: center;
    }

    .status-option:hover {
        border-color: #667eea;
        background: rgba(102, 126, 234, 0.05);
    }

    .status-option input[type="radio"]:checked + .status-label {
        color: white;
    }

    .btn-submit {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
        padding: 0.875rem 2rem;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        color: white;
    }
</style>

<div class="container py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">
                <i class="bi bi-pencil-square"></i> Edit Item
            </h2>
            <p class="text-muted mb-0">Update item information</p>
        </div>
        <a href="{{ route('admin.items.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to Items
        </a>
    </div>

    <!-- Form -->
    <form action="{{ route('admin.items.update', $item->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Basic Information -->
        <div class="form-card">
            <h3 class="form-section-title">
                <i class="bi bi-info-circle"></i> Basic Information
            </h3>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">
                        <i class="bi bi-tag"></i> Item Name *
                    </label>
                    <input 
                        type="text" 
                        class="form-control @error('name') is-invalid @enderror" 
                        id="name" 
                        name="name" 
                        value="{{ old('name', $item->name) }}" 
                        required
                    >
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="category_id" class="form-label">
                        <i class="bi bi-grid"></i> Category *
                    </label>
                    <select 
                        class="form-select @error('category_id') is-invalid @enderror" 
                        id="category_id" 
                        name="category_id" 
                        required
                    >
                        <option value="">Select a category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $item->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 mb-3">
                    <label for="description" class="form-label">
                        <i class="bi bi-text-paragraph"></i> Description
                    </label>
                    <textarea 
                        class="form-control @error('description') is-invalid @enderror" 
                        id="description" 
                        name="description" 
                        rows="4"
                    >{{ old('description', $item->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Pricing & Inventory -->
        <div class="form-card">
            <h3 class="form-section-title">
                <i class="bi bi-currency-dollar"></i> Pricing & Inventory
            </h3>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="rental_rate" class="form-label">
                        <i class="bi bi-cash-coin"></i> Rental Rate (per day) *
                    </label>
                    <div class="input-group">
                        <span class="input-group-text">Rs</span>
                        <input 
                            type="number" 
                            class="form-control @error('rental_rate') is-invalid @enderror" 
                            id="rental_rate" 
                            name="rental_rate" 
                            value="{{ old('rental_rate', $item->rental_rate) }}" 
                            step="0.01" 
                            min="0"
                            required
                        >
                        @error('rental_rate')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="quantity" class="form-label">
                        <i class="bi bi-box-seam"></i> Total Quantity *
                    </label>
                    <input 
                        type="number" 
                        class="form-control @error('quantity') is-invalid @enderror" 
                        id="quantity" 
                        name="quantity" 
                        value="{{ old('quantity', $item->quantity) }}" 
                        min="1"
                        required
                    >
                    @error('quantity')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">
                        Currently available: {{ $item->available_quantity }}
                    </small>
                </div>
            </div>
        </div>

        <!-- Status -->
        <div class="form-card">
            <h3 class="form-section-title">
                <i class="bi bi-toggle-on"></i> Availability Status
            </h3>

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="status-option available">
                        <input type="radio" name="status" value="available" {{ old('status', $item->status) == 'available' ? 'checked' : '' }}>
                        <div class="status-label">
                            <i class="bi bi-check-circle" style="font-size: 2rem;"></i>
                            <h5 class="mt-2 mb-1">Available</h5>
                            <p class="text-muted small mb-0">Ready to rent</p>
                        </div>
                    </label>
                </div>
                <div class="col-md-4">
                    <label class="status-option unavailable">
                        <input type="radio" name="status" value="unavailable" {{ old('status', $item->status) == 'unavailable' ? 'checked' : '' }}>
                        <div class="status-label">
                            <i class="bi bi-x-circle" style="font-size: 2rem;"></i>
                            <h5 class="mt-2 mb-1">Unavailable</h5>
                            <p class="text-muted small mb-0">Not for rent</p>
                        </div>
                    </label>
                </div>
                <div class="col-md-4">
                    <label class="status-option maintenance">
                        <input type="radio" name="status" value="maintenance" {{ old('status', $item->status) == 'maintenance' ? 'checked' : '' }}>
                        <div class="status-label">
                            <i class="bi bi-tools" style="font-size: 2rem;"></i>
                            <h5 class="mt-2 mb-1">Maintenance</h5>
                            <p class="text-muted small mb-0">Under repair</p>
                        </div>
                    </label>
                </div>
            </div>
        </div>

        <!-- Image Upload -->
        <div class="form-card">
            <h3 class="form-section-title">
                <i class="bi bi-image"></i> Item Image
            </h3>

            @if($item->image)
                <div class="mb-3">
                    <label class="form-label">Current Image:</label>
                    <div>
                        <img src="{{ Storage::url($item->image) }}" alt="{{ $item->name }}" class="current-image">
                        <p class="text-muted small mt-2">Upload a new image to replace this</p>
                    </div>
                </div>
            @endif

            <div class="image-upload-area" onclick="document.getElementById('image').click()">
                <input 
                    type="file" 
                    class="d-none" 
                    id="image" 
                    name="image" 
                    accept="image/*"
                    onchange="previewImage(event)"
                >
                <div id="uploadPlaceholder">
                    <i class="bi bi-cloud-upload" style="font-size: 2.5rem; color: #667eea;"></i>
                    <h6 class="mt-2">Click to upload new image</h6>
                    <p class="text-muted small mb-0">PNG, JPG, GIF up to 2MB</p>
                </div>
                <div id="imagePreviewContainer" class="d-none">
                    <img id="imagePreview" style="max-width: 200px; border-radius: 10px;" alt="Preview">
                    <button type="button" class="btn btn-sm btn-danger mt-2" onclick="event.stopPropagation(); removeImage()">
                        <i class="bi bi-trash"></i> Remove
                    </button>
                </div>
            </div>
            @error('image')
                <div class="text-danger mt-2">{{ $message }}</div>
            @enderror
        </div>

        <!-- Submit Buttons -->
        <div class="d-flex gap-3 justify-content-end">
            <a href="{{ route('admin.items.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-x-circle"></i> Cancel
            </a>
            <button type="submit" class="btn btn-submit">
                <i class="bi bi-check-circle"></i> Update Item
            </button>
        </div>
    </form>
</div>

<script>
    function previewImage(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('imagePreview').src = e.target.result;
                document.getElementById('uploadPlaceholder').classList.add('d-none');
                document.getElementById('imagePreviewContainer').classList.remove('d-none');
            }
            reader.readAsDataURL(file);
        }
    }

    function removeImage() {
        document.getElementById('image').value = '';
        document.getElementById('uploadPlaceholder').classList.remove('d-none');
        document.getElementById('imagePreviewContainer').classList.add('d-none');
    }
</script>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
@endsection