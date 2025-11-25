@extends('layouts.app')

@section('content')
<style>
    .item-card {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        height: 100%;
    }

    .item-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.2);
    }

    .item-image {
        height: 180px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3.5rem;
        color: white;
        position: relative;
    }

    .item-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .item-status-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        padding: 0.375rem 0.75rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .status-available {
        background: #d1f2eb;
        color: #0f5132;
    }

    .status-unavailable {
        background: #f8d7da;
        color: #842029;
    }

    .status-maintenance {
        background: #fff3cd;
        color: #664d03;
    }

    .section-card {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        margin-bottom: 2rem;
    }

    .section-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        color: #212529;
    }

    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        color: #6c757d;
    }

    .empty-state i {
        font-size: 4rem;
        opacity: 0.3;
        margin-bottom: 1rem;
    }
</style>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2><i class="bi bi-box-seam"></i> My Items</h2>
            <p class="text-muted mb-0">Manage your rental inventory</p>
        </div>
        <div class="d-flex gap-2">
            <button onclick="history.back()" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </button>
            <a href="{{ route('admin.items.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Add Item
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Search & Filter -->
    <div class="section-card">
        <div class="row">
            <div class="col-md-6">
                <form action="{{ route('admin.items.index') }}" method="GET" class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search items..." value="{{ $search ?? '' }}">
                    <button class="btn btn-outline-secondary" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </form>
            </div>
            <div class="col-md-6">
                <form action="{{ route('admin.items.index') }}" method="GET" class="d-flex gap-2">
                    @if($search)
                        <input type="hidden" name="search" value="{{ $search }}">
                    @endif
                    <select name="category" class="form-select" onchange="this.form.submit()">
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ $category == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>
    </div>

    <!-- Items Grid -->
    @if($items->count() > 0)
        <div class="section-card">
            <div class="row g-4">
                @foreach($items as $item)
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="card item-card">
                            <div class="item-image">
                                @if($item->image)
                                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}">
                                @else
                                    <i class="bi bi-box-seam"></i>
                                @endif
                                <span class="item-status-badge status-{{ $item->status }}">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title mb-2">{{ $item->name }}</h5>
                                <p class="text-muted small mb-2">
                                    <i class="bi bi-tag"></i> {{ $item->category->name }}
                                </p>
                                
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <h5 class="text-primary mb-0">Rs {{ number_format($item->rental_rate, 0) }}</h5>
                                        <small class="text-muted">per day</small>
                                    </div>
                                    <div class="text-end">
                                        @if($item->available_quantity > 0)
                                            <div class="badge bg-success">
                                                {{ $item->available_quantity }}/{{ $item->quantity }} Available
                                            </div>
                                        @else
                                            <div class="badge bg-danger">
                                                Out of Stock
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.items.show', $item->id) }}" class="btn btn-sm btn-outline-primary flex-fill">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                    <a href="{{ route('admin.items.edit', $item->id) }}" class="btn btn-sm btn-outline-success flex-fill">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.items.destroy', $item->id) }}" method="POST" class="flex-fill">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger w-100" onclick="return confirm('Delete this item?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $items->appends(request()->query())->links('custom-pagination') }}
            </div>
        </div>
    @else
        <div class="section-card">
            <div class="empty-state">
                <i class="bi bi-inbox"></i>
                <h4 class="mt-3">No items yet</h4>
                <p class="text-muted">Start by adding your first rental item</p>
                <a href="{{ route('admin.items.create') }}" class="btn btn-primary mt-3">
                    <i class="bi bi-plus-circle"></i> Add Your First Item
                </a>
            </div>
        </div>
    @endif
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
@endsection