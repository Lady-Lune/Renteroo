@extends('layouts.app')

@section('content')
<style>
    .category-card {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        border-left: 4px solid #667eea;
    }

    .category-card:hover {
        transform: translateX(5px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.15);
    }

    .category-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: white;
    }
</style>

<div class="container py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">
                <i class="bi bi-tags"></i> Categories
            </h2>
            <p class="text-muted mb-0">Manage item categories</p>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Add New Category
        </a>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Categories List -->
    @if($categories->count() > 0)
        <div class="row g-3">
            @foreach($categories as $category)
                <div class="col-md-6">
                    <div class="category-card">
                        <div class="d-flex align-items-center">
                            <div class="category-icon me-3">
                                <i class="{{ $category->icon }}"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="mb-1">{{ $category->name }}</h5>
                                <p class="text-muted small mb-2">{{ $category->description }}</p>
                                <span class="badge bg-secondary">{{ $category->items_count }} items</span>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-sm btn-outline-success">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Delete this category?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $categories->links('custom-pagination') }}
        </div>
    @else
        <div class="text-center py-5">
            <i class="bi bi-inbox" style="font-size: 4rem; opacity: 0.3;"></i>
            <h4 class="mt-3">No categories yet</h4>
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary mt-3">
                <i class="bi bi-plus-circle"></i> Add First Category
            </a>
        </div>
    @endif
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
@endsection