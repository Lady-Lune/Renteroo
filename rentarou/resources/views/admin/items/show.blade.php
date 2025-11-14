@extends('layouts.app')

@section('content')
<style>
    .detail-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        padding: 2rem;
        margin-bottom: 2rem;
    }

    .item-detail-image {
        width: 100%;
        max-height: 400px;
        object-fit: cover;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .item-placeholder {
        height: 400px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 8rem;
    }

    .info-label {
        font-weight: 600;
        color: #6c757d;
        font-size: 0.9rem;
        margin-bottom: 0.25rem;
    }

    .info-value {
        font-size: 1.1rem;
        color: #212529;
        font-weight: 600;
    }

    .stat-box {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px;
        padding: 1.5rem;
        text-align: center;
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: 0.25rem;
    }

    .stat-label {
        font-size: 0.9rem;
        opacity: 0.9;
    }

    .status-badge-large {
        display: inline-block;
        padding: 0.5rem 1.5rem;
        border-radius: 50px;
        font-size: 1rem;
        font-weight: 600;
    }

    .rental-item {
        padding: 1rem;
        border-left: 4px solid #667eea;
        background: #f8f9fa;
        border-radius: 10px;
        margin-bottom: 1rem;
    }
</style>

<div class="container py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">
                <i class="bi bi-box-seam"></i> Item Details
            </h2>
            <p class="text-muted mb-0">View item information and statistics</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.items.edit', $item->id) }}" class="btn btn-success">
                <i class="bi bi-pencil"></i> Edit Item
            </a>
            <a href="{{ route('admin.items.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back to Items
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Left Column - Image & Basic Info -->
        <div class="col-lg-5">
            <div class="detail-card">
                @if($item->image)
                    <img src="{{ Storage::url($item->image) }}" alt="{{ $item->name }}" class="item-detail-image">
                @else
                    <div class="item-placeholder">
                        <i class="{{ $item->category->icon }}"></i>
                    </div>
                @endif

                <div class="mt-4">
                    <h3 class="mb-3">{{ $item->name }}</h3>
                    
                    <div class="mb-3">
                        <span class="info-label">Category:</span><br>
                        <span class="badge bg-primary">
                            <i class="{{ $item->category->icon }}"></i> {{ $item->category->name }}
                        </span>
                    </div>

                    <div class="mb-3">
                        <span class="info-label">Status:</span><br>
                        <span class="status-badge-large 
                            @if($item->status == 'available') bg-success
                            @elseif($item->status == 'unavailable') bg-danger
                            @else bg-warning text-dark
                            @endif">
                            <i class="bi bi-
                                @if($item->status == 'available') check-circle
                                @elseif($item->status == 'unavailable') x-circle
                                @else tools
                                @endif"></i>
                            {{ ucfirst($item->status) }}
                        </span>
                    </div>

                    <div class="mb-3">
                        <span class="info-label">Description:</span><br>
                        <p class="text-muted">{{ $item->description ?: 'No description provided' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Details & Stats -->
        <div class="col-lg-7">
            <!-- Pricing & Availability -->
            <div class="detail-card">
                <h4 class="mb-4"><i class="bi bi-info-circle"></i> Pricing & Availability</h4>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="info-label">Rental Rate</div>
                        <div class="info-value text-primary">Rs {{ number_format($item->rental_rate, 2) }} / day</div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <div class="info-label">Total Quantity</div>
                        <div class="info-value">{{ $item->quantity }} units</div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <div class="info-label">Available Now</div>
                        <div class="info-value text-success">{{ $item->available_quantity }} units</div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <div class="info-label">Currently Rented</div>
                        <div class="info-value text-warning">{{ $item->quantity - $item->available_quantity }} units</div>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="detail-card">
                <h4 class="mb-4"><i class="bi bi-graph-up"></i> Rental Statistics</h4>
                
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="stat-box">
                            <div class="stat-number">{{ $stats['total_rentals'] }}</div>
                            <div class="stat-label">Total Rentals</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-box" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                            <div class="stat-number">{{ $stats['active_rentals'] }}</div>
                            <div class="stat-label">Active Rentals</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-box" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                            <div class="stat-number">Rs {{ number_format($stats['total_revenue'], 0) }}</div>
                            <div class="stat-label">Total Revenue</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Rentals -->
            <div class="detail-card">
                <h4 class="mb-4"><i class="bi bi-clock-history"></i> Recent Rentals</h4>
                
                @if($item->rentals->count() > 0)
                    @foreach($item->rentals->take(5) as $rental)
                        <div class="rental-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $rental->user->name }}</strong>
                                    <p class="text-muted small mb-0">
                                        <i class="bi bi-calendar"></i> 
                                        {{ $rental->start_date->format('M d') }} - {{ $rental->end_date->format('M d, Y') }}
                                    </p>
                                </div>
                                <div class="text-end">
                                    <span class="badge 
                                        @if($rental->status == 'active') bg-success
                                        @elseif($rental->status == 'completed') bg-info
                                        @elseif($rental->status == 'pending') bg-warning
                                        @else bg-danger
                                        @endif">
                                        {{ ucfirst($rental->status) }}
                                    </span>
                                    <p class="text-muted small mb-0">Rs {{ number_format($rental->total_amount, 2) }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-inbox" style="font-size: 3rem; color: #6c757d; opacity: 0.3;"></i>
                        <p class="text-muted mt-2">No rental history yet</p>
                    </div>
                @endif
            </div>

            <!-- Action Buttons -->
            <div class="d-flex gap-2">
                <a href="{{ route('admin.items.edit', $item->id) }}" class="btn btn-success flex-fill">
                    <i class="bi bi-pencil"></i> Edit Item
                </a>
                <form action="{{ route('admin.items.destroy', $item->id) }}" method="POST" class="flex-fill" onsubmit="return confirm('Are you sure you want to delete this item?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger w-100">
                        <i class="bi bi-trash"></i> Delete Item
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
@endsection