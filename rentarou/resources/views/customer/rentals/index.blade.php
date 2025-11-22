@extends('layouts.app')

@section('content')
<style>
    .rental-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        padding: 1.5rem;
        margin-bottom: 1rem;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        border-left: 4px solid #e9ecef;
    }

    .rental-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 20px rgba(0,0,0,0.12);
    }

    .rental-card.active {
        border-left-color: #28a745;
    }

    .rental-card.pending {
        border-left-color: #ffc107;
    }

    .rental-card.completed {
        border-left-color: #17a2b8;
    }

    .rental-card.overdue {
        border-left-color: #dc3545;
    }

    .rental-card.cancelled {
        border-left-color: #6c757d;
        opacity: 0.7;
    }

    .stats-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-item {
        text-align: center;
        padding: 0.5rem;
    }

    .stat-number {
        font-size: 2rem;
        font-weight: bold;
        display: block;
    }

    .stat-label {
        font-size: 0.9rem;
        opacity: 0.9;
    }

    .empty-state {
        text-align: center;
        padding: 3rem;
        color: #6c757d;
    }

    .empty-state i {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.3;
    }
</style>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2><i class="bi bi-calendar-check"></i> My Rentals</h2>
            <p class="text-muted mb-0">View your rental history and current bookings</p>
        </div>
        <a href="{{ route('customer.dashboard') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    <!-- Stats Summary -->
    <div class="stats-card">
        <div class="row">
            <div class="col-md-3 col-6">
                <div class="stat-item">
                    <span class="stat-number">{{ $rentals->where('status', 'active')->count() }}</span>
                    <div class="stat-label">Active Rentals</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-item">
                    <span class="stat-number">{{ $rentals->where('status', 'pending')->count() }}</span>
                    <div class="stat-label">Pending</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-item">
                    <span class="stat-number">{{ $rentals->where('status', 'completed')->count() }}</span>
                    <div class="stat-label">Completed</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-item">
                    <span class="stat-number">Rs {{ number_format($rentals->where('status', 'completed')->sum('total_amount'), 0) }}</span>
                    <div class="stat-label">Total Spent</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Rentals List -->
    @if($rentals->count() > 0)
        @foreach($rentals as $rental)
            <div class="rental-card {{ $rental->status }}">
                <div class="row align-items-center">
                    <div class="col-md-3">
                        <h5 class="mb-1">{{ $rental->item->name }}</h5>
                        <p class="text-muted small mb-0">
                            <i class="{{ $rental->item->category->icon }}"></i> {{ $rental->item->category->name }}
                        </p>
                    </div>
                    <div class="col-md-3">
                        <p class="text-muted small mb-0">Rental Period</p>
                        <strong>
                            {{ $rental->start_date->format('M d') }} - {{ $rental->end_date->format('M d, Y') }}
                        </strong>
                        <p class="text-muted small mb-0">{{ $rental->getRentalDays() }} days</p>
                    </div>
                    <div class="col-md-2 text-center">
                        <span class="badge 
                            @if($rental->status == 'active') bg-success
                            @elseif($rental->status == 'completed') bg-info
                            @elseif($rental->status == 'pending') bg-warning
                            @elseif($rental->status == 'overdue') bg-danger
                            @elseif($rental->status == 'cancelled') bg-secondary
                            @else bg-secondary
                            @endif">
                            {{ ucfirst($rental->status) }}
                        </span>

                        @if($rental->isOverdue() && $rental->status == 'active')
                            <div class="text-danger small mt-1">
                                <strong>{{ $rental->getLateDays() }} days overdue</strong>
                            </div>
                        @elseif($rental->status == 'active')
                            @php $daysLeft = $rental->getDaysUntilReturn(); @endphp
                            @if($daysLeft >= 0)
                                <div class="text-info small mt-1">
                                    {{ $daysLeft }} days left
                                </div>
                            @endif
                        @endif
                    </div>
                    <div class="col-md-2 text-center">
                        <p class="text-muted small mb-0">Total Amount</p>
                        <h5 class="text-primary mb-0">Rs {{ number_format($rental->total_amount + $rental->late_fee + $rental->damage_fee, 2) }}</h5>
                        @if($rental->late_fee > 0 || $rental->damage_fee > 0)
                            <small class="text-danger">+ fees applied</small>
                        @endif
                    </div>
                    <div class="col-md-2 text-end">
                        <a href="{{ route('customer.rentals.show', $rental->id) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye"></i> View Details
                        </a>
                    </div>
                </div>
            </div>
        @endforeach

        <div class="mt-4">
            {{ $rentals->links('custom-pagination') }}
        </div>
    @else
        <div class="empty-state">
            <i class="bi bi-calendar-x"></i>
            <h4>No rentals yet</h4>
            <p>You haven't made any rental bookings yet.</p>
            <a href="{{ route('items.index') }}" class="btn btn-primary mt-3">
                <i class="bi bi-search"></i> Browse Available Items
            </a>
        </div>
    @endif
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
@endsection