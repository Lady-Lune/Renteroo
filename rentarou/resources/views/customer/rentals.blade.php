@extends('layouts.app')

@section('content')
<style>
    .customer-hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 3rem 0;
        margin: -24px -12px 2rem -12px;
        border-radius: 0 0 20px 20px;
    }
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
    .rental-card.status-completed { border-left-color: #198754; }
    .rental-card.status-active { border-left-color: #0d6efd; }
    .rental-card.status-overdue { border-left-color: #dc3545; }
    .rental-card.status-cancelled { border-left-color: #6c757d; }

    .section-title {
        font-size: 1.75rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        color: #212529;
    }
    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        background: #f8f9fa;
        border-radius: 15px;
        color: #6c757d;
    }
    .empty-state i {
        font-size: 4rem;
        opacity: 0.3;
        margin-bottom: 1rem;
    }
    .status-badge {
        font-size: 0.8rem;
        font-weight: 600;
        padding: 0.4em 0.8em;
        border-radius: 50px;
    }
</style>

<!-- Hero Section -->
<div class="customer-hero">
    <div class="container">
        <h1 class="display-5 fw-bold">My Rentals</h1>
        <p class="lead">A complete history of all your rented items.</p>
    </div>
</div>

<div class="container">

    <!-- Filter/Search Section (Optional - can be added later) -->
    <!-- For now, we will just list all rentals -->

    <div class="mb-4">
        <h2 class="section-title">
            <i class="bi bi-list-ul"></i> All My Rentals
        </h2>

        @if($rentals->count() > 0)
            @foreach($rentals as $rental)
                @php
                    $statusClass = 'status-active'; // Default
                    if ($rental->status === 'completed') $statusClass = 'status-completed';
                    if ($rental->isOverdue()) $statusClass = 'status-overdue';
                    if ($rental->status === 'cancelled') $statusClass = 'status-cancelled';
                @endphp

                <div class="rental-card {{ $statusClass }}">
                    <div class="row align-items-center">
                        <div class="col-md-1 text-center d-none d-md-block">
                            <i class="{{ $rental->item->category->icon ?? 'bi-box' }}" style="font-size: 2.5rem; color: #667eea;"></i>
                        </div>
                        <div class="col-md-5">
                            <h5 class="mb-1">{{ $rental->item->name }}</h5>
                            <p class="text-muted mb-2">
                                <i class="bi bi-tag"></i> {{ $rental->item->category->name ?? 'Uncategorized' }}
                            </p>
                            <div>
                                <span class="badge bg-light text-dark">
                                    <i class="bi bi-calendar-check"></i> 
                                    Rented: {{ $rental->start_date->format('M d, Y') }}
                                </span>
                                <span class="badge bg-light text-dark">
                                    <i class="bi bi-calendar-x"></i> 
                                    Due: {{ $rental->end_date->format('M d, Y') }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-3 mt-3 mt-md-0">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <span class="status-badge bg-{{ str_replace('status-', '', $statusClass) }} bg-opacity-10 text-{{ str_replace('status-', '', $statusClass) }}">
                                        {{ ucfirst($rental->status) }}
                                        @if($rental->isOverdue())
                                            (Overdue)
                                        @endif
                                    </span>
                                </div>
                                <div>
                                    <div class="fw-bold">Total Amount</div>
                                    <div class="text-primary" style="font-size: 1.2rem; font-weight: 700;">
                                        Rs {{ number_format($rental->total_amount, 2) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 text-md-end mt-3 mt-md-0">
                            <a href="{{ route('customer.rentals.show', $rental->id) }}" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-eye"></i> View Details
                            </a>
                            <a href="{{ route('invoices.show', $rental->invoice->id) }}" class="btn btn-outline-secondary btn-sm mt-1">
                                <i class="bi bi-receipt"></i> View Invoice
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- Pagination -->
            <div class="mt-4">
                {{ $rentals->links() }}
            </div>

        @else
            <div class="empty-state">
                <i class="bi bi-inbox"></i>
                <h4>No Rentals Found</h4>
                <p>You haven't rented any items yet. Why not browse our collection?</p>
                <a href="{{ route('customer.dashboard') }}" class="btn btn-primary mt-3">
                    <i class="bi bi-box-seam"></i> Browse Items
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
@endsection
