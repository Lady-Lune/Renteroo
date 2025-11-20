@extends('layouts.app')

@section('content')
<style>
    .form-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        padding: 2rem;
    }

    .icon-selector {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(60px, 1fr));
        gap: 0.75rem;
        max-height: 300px;
        overflow-y: auto;
        padding: 1rem;
        border: 2px solid #e9ecef;
        border-radius: 10px;
    }

    .icon-option {
        width: 60px;
        height: 60px;
        border: 2px solid #e9ecef;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .icon-option:hover {
        border-color: #667eea;
        background: rgba(102, 126, 234, 0.1);
    }

    .icon-option.selected {
        border-color: #667eea;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
</style>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-pencil-square"></i> Edit Category</h2>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>

    <div class="form-card">
        <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Category Name *</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $category->name) }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea class="form-control" name="description" rows="3">{{ old('description', $category->description) }}</textarea>
            </div>

            <div class="mb-4">
                <label class="form-label">Select Icon *</label>
                <input type="hidden" name="icon" id="selectedIcon" value="{{ old('icon', $category->icon) }}">
                <div class="icon-selector">
                    @php
                    $icons = ['bi-tag', 'bi-bicycle', 'bi-tools', 'bi-camera-video', 'bi-balloon', 'bi-laptop', 'bi-book', 'bi-music-note', 'bi-hammer', 'bi-car-front', 'bi-house', 'bi-briefcase', 'bi-basket', 'bi-brush', 'bi-cup', 'bi-flower1', 'bi-gift', 'bi-headphones', 'bi-lightbulb', 'bi-palette'];
                    @endphp
                    @foreach($icons as $icon)
                        <div class="icon-option {{ old('icon', $category->icon) == $icon ? 'selected' : '' }}" onclick="selectIcon('{{ $icon }}', this)">
                            <i class="{{ $icon }}"></i>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="d-flex gap-3">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Update Category
                </button>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
    function selectIcon(iconClass, element) {
        document.querySelectorAll('.icon-option').forEach(el => el.classList.remove('selected'));
        element.classList.add('selected');
        document.getElementById('selectedIcon').value = iconClass;
    }
</script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
@endsection