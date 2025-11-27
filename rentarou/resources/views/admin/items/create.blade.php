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

    .status-option.selected {
        border-color: #667eea;
        background: rgba(102, 126, 234, 0.1);
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
            <h2><i class="bi bi-plus-circle"></i> Create New Item</h2>
            <p class="text-muted mb-0">Add a new rental item to your inventory</p>
        </div>
        <a href="{{ route('admin.items.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to Items
        </a>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('admin.items.store') }}" method="POST" enctype="multipart/form-data" id="itemForm">
        @csrf

        <div class="row">
            <div class="col-lg-8">
                <!-- Basic Information -->
                <div class="form-card">
                    <h5 class="form-section-title">Basic Information</h5>

                    <div class="row g-3">
                        <div class="col-12">
                            <label for="name" class="form-label">Item Name *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" 
                                   placeholder="Enter item name" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="category_id" class="form-label">Category *</label>
                            <select class="form-select @error('category_id') is-invalid @enderror" 
                                    id="category_id" name="category_id" required>
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

                        <div class="col-12">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4" 
                                      placeholder="Enter item description">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Rental Information -->
                <div class="form-card">
                    <h5 class="form-section-title">Rental Information</h5>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="rental_rate" class="form-label">Daily Rental Rate (Rs) *</label>
                            <div class="input-group">
                                <span class="input-group-text">Rs</span>
                                <input type="number" class="form-control @error('rental_rate') is-invalid @enderror" 
                                       id="rental_rate" name="rental_rate" value="{{ old('rental_rate') }}" 
                                       min="0" step="0.01" placeholder="0.00" required>
                            </div>
                            @error('rental_rate')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="quantity" class="form-label">Total Quantity *</label>
                            <input type="number" class="form-control @error('quantity') is-invalid @enderror" 
                                   id="quantity" name="quantity" value="{{ old('quantity') }}" 
                                   min="1" placeholder="1" required>
                            @error('quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Status -->
                <div class="form-card">
                    <h5 class="form-section-title">Status</h5>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="status-option">
                                <input type="radio" name="status" value="available" {{ old('status', 'available') == 'available' ? 'checked' : '' }}>
                                <div class="status-label">
                                    <i class="bi bi-check-circle text-success"></i>
                                    <div class="fw-bold">Available</div>
                                    <small class="text-muted">Ready for rental</small>
                                </div>
                            </label>
                        </div>
                        <div class="col-md-4">
                            <label class="status-option">
                                <input type="radio" name="status" value="unavailable" {{ old('status') == 'unavailable' ? 'checked' : '' }}>
                                <div class="status-label">
                                    <i class="bi bi-x-circle text-danger"></i>
                                    <div class="fw-bold">Unavailable</div>
                                    <small class="text-muted">Not available for rental</small>
                                </div>
                            </label>
                        </div>
                        <div class="col-md-4">
                            <label class="status-option">
                                <input type="radio" name="status" value="maintenance" {{ old('status') == 'maintenance' ? 'checked' : '' }}>
                                <div class="status-label">
                                    <i class="bi bi-wrench text-warning"></i>
                                    <div class="fw-bold">Maintenance</div>
                                    <small class="text-muted">Under maintenance</small>
                                </div>
                            </label>
                        </div>
                    </div>
                    @error('status')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Image Upload -->
                <div class="form-card">
                    <h5 class="form-section-title">Item Image</h5>
                    
                    <div class="image-upload-area" onclick="document.getElementById('image').click()">
                        <div id="imagePreview" style="display: none;">
                            <img id="previewImg" src="" alt="Preview" class="img-fluid rounded mb-3" style="max-height: 200px;">
                            <p class="text-muted mb-0">Click to change image</p>
                        </div>
                        <div id="uploadPlaceholder">
                            <i class="bi bi-cloud-upload fs-1 text-muted mb-3"></i>
                            <h6>Upload Item Image</h6>
                            <p class="text-muted small mb-0">Click here or drag and drop<br>JPG, PNG, GIF (max 2MB)</p>
                        </div>
                    </div>
                    
                    <input type="file" class="form-control @error('image') is-invalid @enderror" 
                           id="image" name="image" accept="image/*" style="display: none;" onchange="previewImage(this)">
                    @error('image')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="form-card text-center">
                    <button type="submit" class="btn btn-submit btn-lg w-100">
                        <i class="bi bi-plus-circle me-2"></i>Create Item
                    </button>
                    <p class="text-muted small mt-3 mb-0">
                        Make sure all information is correct before creating the item.
                    </p>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('imagePreview').style.display = 'block';
            document.getElementById('uploadPlaceholder').style.display = 'none';
        }
        reader.readAsDataURL(input.files[0]);
    }
}

// Status selection styling
document.addEventListener('DOMContentLoaded', function() {
    const statusOptions = document.querySelectorAll('.status-option');
    const statusInputs = document.querySelectorAll('input[name="status"]');
    
    statusInputs.forEach(input => {
        input.addEventListener('change', function() {
            statusOptions.forEach(option => {
                option.classList.remove('selected');
                if (option.querySelector('input').checked) {
                    option.classList.add('selected');
                }
            });
        });
        
        // Set initial state
        if (input.checked) {
            input.closest('.status-option').classList.add('selected');
        }
    });
});
</script>
@endsection