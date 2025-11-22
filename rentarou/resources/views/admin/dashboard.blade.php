@extends('layouts.app')

@section('content')
<style>
    .dashboard-hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 3rem 0;
        margin: -24px -12px 2rem -12px;
        border-radius: 0 0 20px 20px;
    }

    .stat-card {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 15px;
        padding: 1.5rem;
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        background: rgba(255, 255, 255, 0.25);
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 800;
    }

    .stat-label {
        font-size: 0.9rem;
        opacity: 0.9;
    }

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

    .rental-item {
        padding: 1rem;
        border-left: 4px solid #667eea;
        background: #f8f9fa;
        border-radius: 10px;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }

    .rental-item:hover {
        background: #e9ecef;
        transform: translateX(5px);
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

<!-- Hero Section -->
<div class="dashboard-hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-5 fw-bold mb-3">
                    Admin Dashboard
                </h1>
                <p class="lead mb-0">Welcome back, <strong>{{ auth()->user()->name }}</strong>!</p>
                <p class="mb-0">Manage your rental inventory and track performance</p>
            </div>
            <div class="col-lg-6">
                <div class="row g-3">
                    <div class="col-md-6 col-6">
                        <div class="stat-card text-center">
                            <div class="stat-number">{{ $stats['total_items'] }}</div>
                            <div class="stat-label">Total Items</div>
                        </div>
                    </div>
                    <div class="col-md-6 col-6">
                        <div class="stat-card text-center">
                            <div class="stat-number">{{ $stats['total_rentals'] }}</div>
                            <div class="stat-label">Total Rentals</div>
                        </div>
                    </div>
                    <div class="col-md-6 col-6">
                        <div class="stat-card text-center">
                            <div class="stat-number">{{ $stats['active_rentals'] }}</div>
                            <div class="stat-label">Active Rentals</div>
                        </div>
                    </div>
                    <div class="col-md-6 col-6">
                        <div class="stat-card text-center">
                            <div class="stat-number">Rs {{ number_format($stats['total_revenue'], 0) }}</div>
                            <div class="stat-label">Total Revenue</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <!-- Quick Actions -->
    <div class="section-card">
        <h3 class="section-title">
            <i class="bi bi-lightning-charge"></i> Quick Actions
        </h3>
        <div class="row g-3">
            <div class="col-lg-3 col-md-4 col-6">
                <a href="{{ route('admin.items.create') }}" class="btn btn-primary w-100 d-flex flex-column align-items-center py-3">
                    <i class="bi bi-plus-circle mb-2" style="font-size: 2rem;"></i>
                    <span>Add New Item</span>
                </a>
            </div>
            <div class="col-lg-3 col-md-4 col-6">
                <a href="{{ route('admin.rentals.create') }}" class="btn btn-success w-100 d-flex flex-column align-items-center py-3">
                    <i class="bi bi-calendar-plus mb-2" style="font-size: 2rem;"></i>
                    <span>New Rental</span>
                </a>
            </div>
            <div class="col-lg-3 col-md-4 col-6">
                <a href="{{ route('admin.items.index') }}" class="btn btn-info w-100 d-flex flex-column align-items-center py-3">
                    <i class="bi bi-list-ul mb-2" style="font-size: 2rem;"></i>
                    <span>All Items</span>
                </a>
            </div>
            <div class="col-lg-3 col-md-4 col-6">
                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary w-100 d-flex flex-column align-items-center py-3">
                    <i class="bi bi-tags mb-2" style="font-size: 2rem;"></i>
                    <span>Categories</span>
                </a>
            </div>
        </div>
    </div>

    <!-- My Items Section -->
    <div class="section-card">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="section-title mb-0">
                <i class="bi bi-box-seam"></i> My Items
            </h3>
            <a href="{{ route('admin.items.index') }}" class="btn btn-outline-primary btn-sm">
                View All Items <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        @if($myItems->count() > 0)
            <div class="row g-4">
                @foreach($myItems as $item)
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="card item-card">
                            <div class="item-image">
                                @if($item->image)
                                    <img src="{{ Storage::url($item->image) }}" alt="{{ $item->name }}">
                                @else
                                    <i class="{{ $item->category->icon }}"></i>
                                @endif
                                <span class="item-status-badge status-{{ $item->status }}">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title mb-2">{{ $item->name }}</h5>
                                <p class="text-muted small mb-2">
                                    <i class="{{ $item->category->icon }}"></i> {{ $item->category->name }}
                                </p>
                                
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <h5 class="text-primary mb-0">Rs {{ number_format($item->rental_rate, 0) }}</h5>
                                        <small class="text-muted">per day</small>
                                    </div>
                                    <div class="text-end">
                                        <div class="badge bg-secondary">
                                            {{ $item->available_quantity }}/{{ $item->quantity }}
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.items.show', $item->id) }}" class="btn btn-sm btn-outline-primary flex-fill">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                    <a href="{{ route('admin.items.edit', $item->id) }}" class="btn btn-sm btn-outline-success flex-fill">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <i class="bi bi-inbox"></i>
                <h4>No items yet</h4>
                <p>Start by adding your first rental item</p>
                <a href="{{ route('admin.items.create') }}" class="btn btn-primary mt-3">
                    <i class="bi bi-plus-circle"></i> Add First Item
                </a>
            </div>
        @endif
    </div>

    <!-- Recent Rentals -->
    <div class="section-card">
        <h3 class="section-title">
            <i class="bi bi-clock-history"></i> Recent Rentals
        </h3>

        @if($recent_rentals->count() > 0)
            @foreach($recent_rentals as $rental)
                <div class="rental-item">
                    <div class="row align-items-center">
                        <div class="col-md-4">
                            <strong>{{ $rental->item->name }}</strong>
                            <p class="text-muted small mb-0">
                                <i class="bi bi-person"></i> 
                                @if($rental->is_guest)
                                    {{ $rental->guest_name }} <small class="text-warning">(Guest)</small>
                                @else
                                    {{ $rental->user->name }}
                                @endif
                            </p>
                        </div>
                        <div class="col-md-4">
                            <p class="text-muted small mb-0">
                                <i class="bi bi-calendar"></i> 
                                {{ $rental->start_date->format('M d') }} - {{ $rental->end_date->format('M d, Y') }}
                            </p>
                        </div>
                        <div class="col-md-2 text-center">
                            <span class="badge 
                                @if($rental->status == 'active') bg-success
                                @elseif($rental->status == 'completed') bg-info
                                @elseif($rental->status == 'pending') bg-warning
                                @else bg-danger
                                @endif">
                                {{ ucfirst($rental->status) }}
                            </span>
                        </div>
                        <div class="col-md-2 text-end">
                            <strong>Rs {{ number_format($rental->total_amount, 2) }}</strong>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="empty-state">
                <i class="bi bi-inbox"></i>
                <h5>No rental history yet</h5>
                <p class="text-muted">Rentals will appear here once customers start booking your items</p>
            </div>
        @endif
    </div>
</div>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
@endsection