@extends('layouts.app')

@section('content')
<style>
    /* Hero Section */
    .customer-hero {
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

    /* Search Section */
    .search-section {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        margin-bottom: 2rem;
    }

    .search-input {
        border-radius: 50px;
        border: 2px solid #e9ecef;
        padding: 0.75rem 1.5rem;
        font-size: 1rem;
    }

    .search-input:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
    }

    .filter-btn {
        border-radius: 50px;
        padding: 0.5rem 1.25rem;
        border: 2px solid #e9ecef;
        background: white;
        transition: all 0.3s ease;
    }

    .filter-btn.active,
    .filter-btn:hover {
        background: #667eea;
        color: white;
        border-color: #667eea;
    }

    /* Item Cards */
    .item-card {
        border: none;
        border-radius: 15px;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        height: 100%;
    }

    .item-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.2);
    }

    .item-image {
        height: 200px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 4rem;
        color: white;
    }

    .item-category-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(255, 255, 255, 0.95);
        padding: 0.25rem 0.75rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
        color: #667eea;
    }

    .item-price {
        font-size: 1.5rem;
        font-weight: 700;
        color: #667eea;
    }

    .availability-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.25rem 0.75rem;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .availability-badge.available {
        background: #d1f2eb;
        color: #0f5132;
    }

    .availability-badge.limited {
        background: #fff3cd;
        color: #664d03;
    }

    .btn-rent {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 50px;
        padding: 0.75rem 1.5rem;
        color: white;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-rent:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        color: white;
    }

    /* Rental Cards */
    .rental-card {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        border-left: 4px solid #667eea;
        transition: all 0.3s ease;
    }

    .rental-card:hover {
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        transform: translateX(5px);
    }

    .rental-card.overdue {
        border-left-color: #dc3545;
    }

    .rental-card.warning {
        border-left-color: #ffc107;
    }

    .alert-custom {
        border-radius: 15px;
        border: none;
        padding: 1.25rem;
    }

    .section-title {
        font-size: 1.75rem;
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

    .quick-actions-row {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .quick-actions-row > div {
        flex: 1 1 calc(33.333% - 0.66rem);
        min-width: 140px;
    }

    @media (max-width: 1200px) {
        .quick-actions-row > div {
            flex: 1 1 calc(50% - 0.5rem);
        }
    }

    @media (max-width: 768px) {
        .quick-actions-row > div {
            flex: 1 1 calc(50% - 0.5rem);
        }
    }

    /* Pagination Fixes - Clean Approach */
    .items-section .pagination-container {
        font-size: 0.875rem;
        isolation: isolate; /* Create new stacking context */
    }
    
    .items-section .pagination-container .pagination {
        margin: 0;
        justify-content: center;
        font-size: inherit;
    }

    .items-section .pagination-container .pagination .page-item .page-link {
        color: #667eea;
        border: 1px solid #dee2e6;
        font-size: 0.875rem;
        padding: 0.5rem 0.75rem;
        line-height: 1.25;
        height: 2.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
    }

    .items-section .pagination-container .pagination .page-item .page-link:hover {
        color: white;
        background-color: #667eea;
        border-color: #667eea;
    }

    .items-section .pagination-container .pagination .page-item.active .page-link {
        background-color: #667eea;
        border-color: #667eea;
        color: white;
    }

    /* Target Bootstrap Icons specifically in pagination */
    .items-section .pagination-container .pagination .page-link i,
    .items-section .pagination-container .pagination .page-link .bi {
        font-size: 0.75rem;
        width: auto;
        height: auto;
    }

    /* Reset any inherited large font sizes */
    .items-section .pagination-container .pagination .page-link svg {
        width: 1rem;
        height: 1rem;
    }
</style>

<!-- Hero Section -->
<div class="customer-hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-5 fw-bold mb-3">
                    Welcome back, {{ auth()->user()->name }}!
                </h1>
                <p class="lead mb-0">Browse available items and manage your rentals</p>
            </div>
            <div class="col-lg-6">
                <div class="row g-3">
                    <div class="col-md-4 col-6">
                        <div class="stat-card text-center">
                            <div class="stat-number">{{ $stats['active_rentals'] }}</div>
                            <div class="stat-label">Active Rentals</div>
                        </div>
                    </div>
                    <div class="col-md-4 col-6">
                        <div class="stat-card text-center">
                            <div class="stat-number">{{ $stats['past_rentals'] }}</div>
                            <div class="stat-label">Completed</div>
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
                        <div class="stat-card text-center">
                            <div class="stat-number">Rs {{ number_format($stats['total_spent'], 0) }}</div>
                            <div class="stat-label">Total Spent</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <!-- Alerts Section -->
    @if($overdueRentals->count() > 0)
        <div class="alert alert-danger alert-custom mb-3">
            <div class="d-flex align-items-center">
                <i class="bi bi-exclamation-triangle-fill me-3" style="font-size: 2rem;"></i>
                <div>
                    <h5 class="mb-1">Overdue Rentals!</h5>
                    <p class="mb-0">You have {{ $overdueRentals->count() }} overdue rental(s). Please return them as soon as possible to avoid additional fees.</p>
                </div>
            </div>
        </div>
    @endif

    @if($upcomingReturns->count() > 0)
        <div class="alert alert-warning alert-custom mb-3">
            <div class="d-flex align-items-center">
                <i class="bi bi-clock-fill me-3" style="font-size: 2rem;"></i>
                <div>
                    <h5 class="mb-1">Upcoming Returns</h5>
                    <p class="mb-0">{{ $upcomingReturns->count() }} rental(s) need to be returned within the next 3 days.</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Active Rentals Section -->
    @if($activeRentals->count() > 0)
        <div class="mb-4">
            <h2 class="section-title">
                <i class="bi bi-clock-history"></i> My Active Rentals
            </h2>
            
            @foreach($activeRentals as $rental)
                <div class="rental-card {{ $rental->isOverdue() ? 'overdue' : ($rental->getDaysUntilReturn() <= 3 ? 'warning' : '') }}">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h5 class="mb-1">{{ $rental->item->name }}</h5>
                            <p class="text-muted mb-2">
                                <i class="bi bi-tag"></i> {{ $rental->item->category->name }}
                            </p>
                            <div>
                                <span class="badge bg-primary">
                                    <i class="bi bi-calendar"></i> 
                                    {{ $rental->start_date->format('M d') }} - {{ $rental->end_date->format('M d, Y') }}
                                </span>
                                @if($rental->isOverdue())
                                    <span class="badge bg-danger ms-2">
                                        <i class="bi bi-exclamation-triangle"></i> 
                                        {{ $rental->getLateDays() }} days overdue
                                    </span>
                                @elseif($rental->getDaysUntilReturn() <= 3)
                                    <span class="badge bg-warning text-dark ms-2">
                                        <i class="bi bi-clock"></i> 
                                        Due in {{ $rental->getDaysUntilReturn() }} day(s)
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3 text-md-center mt-3 mt-md-0">
                            <div class="mb-1">
                                <strong>Total Amount:</strong>
                            </div>
                            <div class="text-primary" style="font-size: 1.5rem; font-weight: 700;">
                                Rs {{ number_format($rental->total_amount, 2) }}
                            </div>
                        </div>
                        <div class="col-md-3 text-md-end mt-3 mt-md-0">
                            <a href="{{ route('customer.rentals.show', $rental->id) }}" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-eye"></i> View Details
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

     <!-- Quick Actions -->
    <div class="card mb-4" style="border-radius: 15px; border: none; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
        <div class="card-body p-4">
            <h5 class="mb-3"><i class="bi bi-lightning-charge"></i> Quick Actions</h5>
            <div class="quick-actions-row">
                <div>
                    <a href="{{ route('customer.rentals.index') }}" class="btn btn-primary w-100 d-flex flex-column align-items-center py-3">
                        <i class="bi bi-list-ul mb-2" style="font-size: 2rem;"></i>
                        <span>View All Rentals</span>
                    </a>
                </div>
                <div>
                    <a href="mailto:support@rentarou.com?subject=Customer Support Request" class="btn btn-info w-100 d-flex flex-column align-items-center py-3">
                        <i class="bi bi-question-circle mb-2" style="font-size: 2rem;"></i>
                        <span>Help & Support</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Search & Filter Section -->
    <div class="search-section">
        <form method="GET" action="{{ route('customer.dashboard') }}">
            <div class="row g-3 align-items-center">
                <div class="col-lg-6">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0" style="border-radius: 50px 0 0 50px; border-right: none;">
                            <i class="bi bi-search"></i>
                        </span>
                        <input 
                            type="text" 
                            name="search" 
                            class="form-control search-input border-start-0" 
                            placeholder="Search for items..."
                            value="{{ $search }}"
                            style="border-left: none; padding-left: 0;"
                        >
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="d-flex flex-wrap gap-2">
                        <button type="submit" name="category" value="" class="filter-btn {{ !$categoryFilter ? 'active' : '' }}">
                            All Items
                        </button>
                        @foreach($categories->take(4) as $category)
                            <button type="submit" name="category" value="{{ $category->id }}" class="filter-btn {{ $categoryFilter == $category->id ? 'active' : '' }}">
                                <i class="{{ $category->icon }}"></i> {{ $category->name }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Available Items Section -->
    <div class="mb-5 items-section">
        <h2 class="section-title">
            <i class="bi bi-box-seam"></i> Available Items to Rent
        </h2>

        @if($availableItems->count() > 0)
            <div class="row g-4">
                @foreach($availableItems as $item)
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="card item-card">
                            <div class="item-image position-relative">
                                <i class="{{ $item->category->icon }}"></i>
                                <span class="item-category-badge">
                                    {{ $item->category->name }}
                                </span>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title mb-2">{{ $item->name }}</h5>
                                <p class="card-text text-muted small mb-3" style="height: 40px; overflow: hidden;">
                                    {{ Str::limit($item->description, 60) }}
                                </p>
                                
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="item-price">
                                        Rs {{ number_format($item->rental_rate, 0) }}
                                        <small class="text-muted" style="font-size: 0.75rem;">/day</small>
                                    </div>
                                    <span class="availability-badge {{ $item->available_quantity > 5 ? 'available' : 'limited' }}">
                                        <i class="bi bi-check-circle-fill"></i>
                                        {{ $item->available_quantity }} available
                                    </span>
                                </div>
                                
                                <a href="{{ route('items.show', $item->id) }}" class="btn btn-rent w-100">
                                    <i class="bi bi-calendar-check"></i> Rent Now
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                <div class="pagination-container">
                    {{ $availableItems->links('custom-pagination') }}
                </div>
            </div>
        @else
            <div class="empty-state">
                <i class="bi bi-inbox"></i>
                <h4>No items found</h4>
                <p>Try adjusting your search or filters</p>
                <a href="{{ route('customer.dashboard') }}" class="btn btn-primary">Clear Filters</a>
            </div>
        @endif
    </div>
</div>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
@endsection