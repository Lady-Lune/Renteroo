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

    .form-label {
        font-weight: 600;
        color: #212529;
        margin-bottom: 0.5rem;
    }

    .form-control, .form-select {
        border-radius: 10px;
        border: 2px solid #e9ecef;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
    }

    .image-upload-area {
        border: 3px dashed #e9ecef;
        border-radius: 15px;
        padding: 3rem 2rem;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
        background: #f8f9fa;
    }

    .image-upload-area:hover {
        border-color: #667eea;
        background: rgba(102, 126, 234, 0.05);
    }

    .image-upload-area.dragover {
        border-color: #667eea;
        background: rgba(102, 126, 234, 0.1);
    }

    .image-preview {
        max-width: 300px;
        max-height: 300px;
        margin: 1rem auto;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
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

    .btn-cancel {
        background: white;
        border: 2px solid #e9ecef;
        color: #6c757d;
        padding: 0.875rem 2rem;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-cancel:hover {
        border-color: #6c757d;
        color: #495057;
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

    .status-option input[type="radio"] {
        display: none;
    }

    .status-option input[type="radio"]:checked + .status-label {
        color: white;
    }

    .status-option input[type="radio"]:checked ~ * {
        color: white;
    }

    .status-option.available input[type="radio"]:checked ~ * {
        background: #198754;
        border-radius: 8px;
        padding: 0.5rem;
    }

    .status-option.unavailable input[type="radio"]:checked ~ * {
        background: #dc3545;
        border-radius: 8px;
        padding: 0.5rem;
    }

    .status-option.maintenance input[type="radio"]:checked ~ * {
        background: #ffc107;
        border-radius: 8px;
        padding: 0.5rem;
        color: #000 !important;
    }
</style>

<div class="container py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">
                <i class="bi bi-plus-circle"></i> Add New Item
            </h2>
            <p class="text-muted mb-0">Create a new rental item in your inventory</p>
        </div>
        <a href="{{ route('admin.items.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to Items
        </a>
    </div>

    <!-- Form -->
    <form action="{{ route('admin.items.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

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
                        value="{{ old('name') }}" 
                        placeholder="e.g., Mountain Bike, DSLR Camera"
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
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                        placeholder="Provide detailed information about the item..."
                    >{{ old('description') }}</textarea>
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
                            value="{{ old('rental_rate') }}" 
                            step="0.01" 
                            min="0"
                            placeholder="0.00"
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
                        value="{{ old('quantity', 1) }}" 
                        min="1"
                        required
                    >
                    @error('quantity')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">How many units do you have?</small>
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
                        <input type="radio" name="status" value="available" {{ old('status', 'available') == 'available' ? 'checked' : '' }}>
                        <div class="status-label">
                            <i class="bi bi-check-circle" style="font-size: 2rem;"></i>
                            <h5 class="mt-2 mb-1">Available</h5>
                            <p class="text-muted small mb-0">Ready to rent</p>
                        </div>
                    </label>
                </div>
                <div class="col-md-4">
                    <label class="status-option unavailable">
                        <input type="radio" name="status" value="unavailable" {{ old('status') == 'unavailable' ? 'checked' : '' }}>
                        <div class="status-label">
                            <i class="bi bi-x-circle" style="font-size: 2rem;"></i>
                            <h5 class="mt-2 mb-1">Unavailable</h5>
                            <p class="text-muted small mb-0">Not for rent</p>
                        </div>
                    </label>
                </div>
                <div class="col-md-4">
                    <label class="status-option maintenance">
                        <input type="radio" name="status" value="maintenance" {{ old('status') == 'maintenance' ? 'checked' : '' }}>
                        <div class="status-label">
                            <i class="bi bi-tools" style="font-size: 2rem;"></i>
                            <h5 class="mt-2 mb-1">Maintenance</h5>
                            <p class="text-muted small mb-0">Under repair</p>
                        </div>
                    </label>
                </div>
            </div>
            @error('status')
                <div class="text-danger mt-2">{{ $message }}</div>
            @enderror
        </div>

        <!-- Image Upload -->
        <div class="form-card">
            <h3 class="form-section-title">
                <i class="bi bi-image"></i> Item Image (Optional)
            </h3>

            <div class="image-upload-area" id="imageUploadArea">
                <input 
                    type="file" 
                    class="d-none" 
                    id="image" 
                    name="image" 
                    accept="image/*"
                    onchange="previewImage(event)"
                >
                <div id="uploadPlaceholder">
                    <i class="bi bi-cloud-upload" style="font-size: 3rem; color: #667eea;"></i>
                    <h5 class="mt-3">Click to upload or drag and drop</h5>
                    <p class="text-muted mb-0">PNG, JPG, GIF up to 2MB</p>
                </div>
                <div id="imagePreviewContainer" class="d-none">
                    <img id="imagePreview" class="image-preview" alt="Preview">
                    <button type="button" class="btn btn-sm btn-danger mt-3" onclick="removeImage()">
                        <i class="bi bi-trash"></i> Remove Image
                    </button>
                </div>
            </div>
            @error('image')
                <div class="text-danger mt-2">{{ $message }}</div>
            @enderror
        </div>

        <!-- Submit Buttons -->
        <div class="d-flex gap-3 justify-content-end">
            <a href="{{ route('admin.items.index') }}" class="btn btn-cancel">
                <i class="bi bi-x-circle"></i> Cancel
            </a>
            <button type="submit" class="btn btn-submit">
                <i class="bi bi-check-circle"></i> Create Item
            </button>
        </div>
    </form>
</div>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
@endsection