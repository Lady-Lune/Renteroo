@extends('layouts.app')

@section('content')
<style>
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
        transform: translateX(5px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }

    .rental-card.overdue {
        border-left-color: #dc3545;
    }

    .rental-card.pending {
        border-left-color: #ffc107;
    }

    .rental-card.completed {
        border-left-color: #198754;
    }
</style>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2><i class="bi bi-calendar-check"></i> Rentals Management</h2>
            <p class="text-muted mb-0">Manage all rental bookings</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back to Dashboard
            </a>
            <a href="{{ route('admin.rentals.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Create New Rental
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Stats Summary -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h3>{{ $rentals->where('status', 'pending')->count() }}</h3>
                    <p class="mb-0">Pending</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h3>{{ $rentals->where('status', 'active')->count() }}</h3>
                    <p class="mb-0">Active</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h3>{{ $rentals->where('status', 'completed')->count() }}</h3>
                    <p class="mb-0">Completed</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h3>{{ $rentals->where('status', 'overdue')->count() }}</h3>
                    <p class="mb-0">Overdue</p>
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
                            <i class="bi bi-person"></i> 
                            @if($rental->is_guest)
                                {{ $rental->guest_name }} <small class="text-warning">(Guest)</small>
                            @else
                                {{ $rental->user->name }}
                            @endif
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
                            @else bg-secondary
                            @endif">
                            {{ ucfirst($rental->status) }}
                        </span>
                    </div>
                    <div class="col-md-2 text-center">
                        <p class="text-muted small mb-0">Total Amount</p>
                        <h5 class="text-primary mb-0">Rs {{ number_format($rental->total_amount, 2) }}</h5>
                    </div>
                    <div class="col-md-2 text-end">
                        <a href="{{ route('admin.rentals.show', $rental->id) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye"></i> View Details
                        </a>
                    </div>
                </div>
            </div>
        @endforeach

        <div class="mt-4">
            {{ $rentals->custom-pagination') }}
        </div>
    @else
        <div class="text-center py-5">
            <i class="bi bi-inbox" style="font-size: 4rem; opacity: 0.3;"></i>
            <h4 class="mt-3">No rentals yet</h4>
            <p class="text-muted">Create your first rental booking</p>
            <a href="{{ route('admin.rentals.create') }}" class="btn btn-primary mt-3">
                <i class="bi bi-plus-circle"></i> Create Rental
            </a>
        </div>
    @endif
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
@endsection