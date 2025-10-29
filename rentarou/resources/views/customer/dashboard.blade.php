@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="mb-2">
                <i class="bi bi-person-circle"></i> My Dashboard
            </h2>
            <p class="text-muted">Welcome back, <strong>{{ auth()->user()->name }}</strong>! Manage your rentals and explore new items.</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <!-- Active Rentals -->
        <div class="col-lg-4 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">ACTIVE RENTALS</p>
                            <h2 class="mb-0 fw-bold text-primary">{{ $stats['active_rentals'] }}</h2>
                            <p class="text-muted small mb-0 mt-2">
                                <i class="bi bi-calendar-event"></i> 2 ending this week
                            </p>
                        </div>
                        <div class="bg-primary bg-opacity-10 rounded p-3">
                            <i class="bi bi-clock-history text-primary" style="font-size: 1.8rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Past Rentals -->
        <div class="col-lg-4 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">COMPLETED RENTALS</p>
                            <h2 class="mb-0 fw-bold text-success">{{ $stats['past_rentals'] }}</h2>
                            <p class="text-muted small mb-0 mt-2">
                                <i class="bi bi-check-circle"></i> All time
                            </p>
                        </div>
                        <div class="bg-success bg-opacity-10 rounded p-3">
                            <i class="bi bi-check-circle text-success" style="font-size: 1.8rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Spent -->
        <div class="col-lg-4 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">TOTAL SPENT</p>
                            <h2 class="mb-0 fw-bold text-info">Rs {{ number_format($stats['total_spent'], 2) }}</h2>
                            <p class="text-muted small mb-0 mt-2">
                                <i class="bi bi-graph-up"></i> Lifetime
                            </p>
                        </div>
                        <div class="bg-info bg-opacity-10 rounded p-3">
                            <i class="bi bi-currency-dollar text-info" style="font-size: 1.8rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="mb-0">
                        <i class="bi bi-lightning-charge"></i> Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-lg-3 col-md-4 col-6">
                            <a href="{{ route('items.index') }}" class="btn btn-outline-primary w-100 d-flex flex-column align-items-center py-3">
                                <i class="bi bi-search mb-2" style="font-size: 1.5rem;"></i>
                                <span class="small">Browse Items</span>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-4 col-6">
                            <a href="{{ route('customer.rentals.index') }}" class="btn btn-outline-success w-100 d-flex flex-column align-items-center py-3">
                                <i class="bi bi-calendar-check mb-2" style="font-size: 1.5rem;"></i>
                                <span class="small">My Rentals</span>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-4 col-6">
                            <a href="{{ route('invoices.index') }}" class="btn btn-outline-info w-100 d-flex flex-column align-items-center py-3">
                                <i class="bi bi-receipt mb-2" style="font-size: 1.5rem;"></i>
                                <span class="small">My Invoices</span>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-4 col-6">
                            <a href="#" class="btn btn-outline-warning w-100 d-flex flex-column align-items-center py-3">
                                <i class="bi bi-bell mb-2" style="font-size: 1.5rem;"></i>
                                <span class="small">Notifications</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Rentals & Upcoming Returns -->
    <div class="row mb-4">
        <!-- Active Rentals -->
        <div class="col-lg-8 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pt-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">Active Rentals</h5>
                        <p class="text-muted small mb-0">Your current ongoing rentals</p>
                    </div>
                    <a href="{{ route('customer.rentals.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    @if(count($active_rentals) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Item</th>
                                        <th>Start Date</th>
                                        <th>Return By</th>
                                        <th>Status</th>
                                        <th>Amount</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($active_rentals as $rental)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="bg-light rounded p-2 me-2">
                                                    <i class="bi bi-box-seam"></i>
                                                </div>
                                                <span>{{ $rental->item->name }}</span>
                                            </div>
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($rental->start_date)->format('M d, Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($rental->end_date)->format('M d, Y') }}</td>
                                        <td>
                                            <span class="badge bg-success">Active</span>
                                        </td>
                                        <td><strong>Rs {{ number_format($rental->total_amount, 2) }}</strong></td>
                                        <td>
                                            <a href="{{ route('customer.rentals.show', $rental->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-3 mb-3">You have no active rentals.</p>
                            <a href="{{ route('items.index') }}" class="btn btn-primary">
                                <i class="bi bi-search"></i> Browse Items
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Upcoming Returns & Reminders -->
        <div class="col-lg-4 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="mb-0">
                        <i class="bi bi-bell"></i> Reminders
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning mb-3">
                        <div class="d-flex align-items-start">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <div>
                                <strong>Return Due Soon</strong>
                                <p class="small mb-0">2 items need to be returned within 3 days</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="alert alert-info mb-3">
                        <div class="d-flex align-items-start">
                            <i class="bi bi-info-circle me-2"></i>
                            <div>
                                <strong>New Items Available</strong>
                                <p class="small mb-0">5 new items added this week</p>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-success mb-0">
                        <div class="d-flex align-items-start">
                            <i class="bi bi-check-circle me-2"></i>
                            <div>
                                <strong>Good Standing</strong>
                                <p class="small mb-0">No overdue rentals</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Available Items Preview -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">Available Items</h5>
                        <p class="text-muted small mb-0">Popular items ready to rent</p>
                    </div>
                    <a href="{{ route('items.index') }}" class="btn btn-sm btn-primary">Browse All Items</a>
                </div>
                <div class="card-body">
                    @if(count($available_items) > 0)
                        <div class="row g-3">
                            @foreach($available_items as $item)
                            <div class="col-lg-3 col-md-4 col-6">
                                <div class="card h-100 border">
                                    <div class="card-body text-center">
                                        <div class="bg-light rounded p-4 mb-3">
                                            <i class="bi bi-box-seam text-primary" style="font-size: 2rem;"></i>
                                        </div>
                                        <h6 class="card-title mb-2">{{ $item->name }}</h6>
                                        <p class="card-text text-muted small mb-2">{{ $item->category->name }}</p>
                                        <p class="mb-3">
                                            <strong class="text-primary">Rs {{ number_format($item->rental_rate, 2) }}</strong>
                                            <span class="text-muted small">/ day</span>
                                        </p>
                                        <a href="{{ route('items.show', $item->id) }}" class="btn btn-sm btn-outline-primary w-100">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-3">No items available at the moment.</p>
                            <p class="small text-muted">Check back later for new items!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

<style>
    .card {
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }
    .avatar {
        font-weight: bold;
        font-size: 0.875rem;
    }
</style>
@endsection