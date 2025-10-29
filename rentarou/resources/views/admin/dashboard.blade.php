@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="mb-2">
                <i class="bi bi-speedometer2"></i> Admin Dashboard
            </h2>
            <p class="text-muted">Welcome back, <strong>{{ auth()->user()->name }}</strong>! Here's your business overview.</p>
        </div>
        <div class="col-md-4 text-end">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-outline-primary btn-sm">Today</button>
                <button type="button" class="btn btn-outline-primary btn-sm active">This Week</button>
                <button type="button" class="btn btn-outline-primary btn-sm">This Month</button>
            </div>
        </div>
    </div>

    <!-- Statistics Cards (Row 1) -->
    <div class="row mb-4">
        <!-- Total Items -->
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">TOTAL ITEMS</p>
                            <h2 class="mb-0 fw-bold">{{ $stats['total_items'] }}</h2>
                            <p class="text-success small mb-0 mt-2">
                                <i class="bi bi-arrow-up"></i> 12% vs last month
                            </p>
                        </div>
                        <div class="bg-primary bg-opacity-10 rounded p-3">
                            <i class="bi bi-box-seam text-primary" style="font-size: 1.8rem;"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pt-0">
                    <a href="{{ route('admin.items.index') }}" class="text-decoration-none small">
                        View all items <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Total Rentals -->
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">TOTAL RENTALS</p>
                            <h2 class="mb-0 fw-bold">{{ $stats['total_rentals'] }}</h2>
                            <p class="text-success small mb-0 mt-2">
                                <i class="bi bi-arrow-up"></i> 8% vs last month
                            </p>
                        </div>
                        <div class="bg-success bg-opacity-10 rounded p-3">
                            <i class="bi bi-calendar-check text-success" style="font-size: 1.8rem;"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pt-0">
                    <a href="{{ route('admin.rentals.index') }}" class="text-decoration-none small">
                        View all rentals <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Active Rentals -->
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">ACTIVE RENTALS</p>
                            <h2 class="mb-0 fw-bold">{{ $stats['active_rentals'] }}</h2>
                            <p class="text-warning small mb-0 mt-2">
                                <i class="bi bi-dash"></i> 3 overdue
                            </p>
                        </div>
                        <div class="bg-warning bg-opacity-10 rounded p-3">
                            <i class="bi bi-clock-history text-warning" style="font-size: 1.8rem;"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pt-0">
                    <a href="{{ route('admin.rentals.index') }}" class="text-decoration-none small">
                        Manage rentals <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">TOTAL REVENUE</p>
                            <h2 class="mb-0 fw-bold">Rs {{ number_format($stats['total_revenue'], 2) }}</h2>
                            <p class="text-success small mb-0 mt-2">
                                <i class="bi bi-arrow-up"></i> 15% vs last month
                            </p>
                        </div>
                        <div class="bg-info bg-opacity-10 rounded p-3">
                            <i class="bi bi-currency-dollar text-info" style="font-size: 1.8rem;"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pt-0">
                    <a href="#" class="text-decoration-none small">
                        View reports <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <!-- Revenue Chart -->
        <div class="col-lg-8 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="mb-0">Revenue Overview</h5>
                    <p class="text-muted small mb-0">Monthly revenue for the last 6 months</p>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" height="100"></canvas>
                </div>
            </div>
        </div>

        <!-- Rental Status Pie Chart -->
        <div class="col-lg-4 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="mb-0">Rental Status</h5>
                    <p class="text-muted small mb-0">Current distribution</p>
                </div>
                <div class="card-body d-flex align-items-center justify-content-center">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions & Alerts Row -->
    <div class="row mb-4">
        <!-- Quick Actions -->
        <div class="col-lg-8 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="mb-0">
                        <i class="bi bi-lightning-charge"></i> Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3 col-6">
                            <a href="{{ route('admin.items.create') }}" class="btn btn-outline-primary w-100 d-flex flex-column align-items-center py-3">
                                <i class="bi bi-plus-circle mb-2" style="font-size: 1.5rem;"></i>
                                <span class="small">Add Item</span>
                            </a>
                        </div>
                        <div class="col-md-3 col-6">
                            <a href="{{ route('admin.rentals.create') }}" class="btn btn-outline-success w-100 d-flex flex-column align-items-center py-3">
                                <i class="bi bi-calendar-plus mb-2" style="font-size: 1.5rem;"></i>
                                <span class="small">New Rental</span>
                            </a>
                        </div>
                        <div class="col-md-3 col-6">
                            <a href="{{ route('admin.items.index') }}" class="btn btn-outline-info w-100 d-flex flex-column align-items-center py-3">
                                <i class="bi bi-list-ul mb-2" style="font-size: 1.5rem;"></i>
                                <span class="small">All Items</span>
                            </a>
                        </div>
                        <div class="col-md-3 col-6">
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary w-100 d-flex flex-column align-items-center py-3">
                                <i class="bi bi-tags mb-2" style="font-size: 1.5rem;"></i>
                                <span class="small">Categories</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alerts -->
        <div class="col-lg-4 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="mb-0">
                        <i class="bi bi-exclamation-triangle"></i> Alerts
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning mb-2">
                        <strong>3</strong> overdue rentals
                    </div>
                    <div class="alert alert-info mb-2">
                        <strong>5</strong> items low in stock
                    </div>
                    <div class="alert alert-success mb-0">
                        <strong>2</strong> pending returns today
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Rentals Table -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">Recent Rentals</h5>
                        <p class="text-muted small mb-0">Latest rental transactions</p>
                    </div>
                    <a href="{{ route('admin.rentals.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    @if(count($recent_rentals) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Customer</th>
                                        <th>Item</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Status</th>
                                        <th>Amount</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recent_rentals as $rental)
                                    <tr>
                                        <td><span class="badge bg-secondary">#{{ $rental->id }}</span></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar bg-primary text-white rounded-circle me-2" style="width:32px; height:32px; display:flex; align-items:center; justify-content:center;">
                                                    {{ substr($rental->user->name, 0, 1) }}
                                                </div>
                                                <span>{{ $rental->user->name }}</span>
                                            </div>
                                        </td>
                                        <td>{{ $rental->item->name }}</td>
                                        <td>{{ \Carbon\Carbon::parse($rental->start_date)->format('M d, Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($rental->end_date)->format('M d, Y') }}</td>
                                        <td>
                                            @if($rental->status == 'active')
                                                <span class="badge bg-success">Active</span>
                                            @elseif($rental->status == 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @elseif($rental->status == 'completed')
                                                <span class="badge bg-info">Completed</span>
                                            @else
                                                <span class="badge bg-danger">Overdue</span>
                                            @endif
                                        </td>
                                        <td><strong>Rs {{ number_format($rental->total_amount, 2) }}</strong></td>
                                        <td>
                                            <a href="{{ route('admin.rentals.show', $rental->id) }}" class="btn btn-sm btn-outline-primary">
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
                            <p class="text-muted mt-3">No recent rentals found.</p>
                            <a href="{{ route('admin.rentals.create') }}" class="btn btn-primary">Create First Rental</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Initialize Charts -->
<script>
    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Revenue (Rs)',
                data: [12000, 19000, 15000, 25000, 22000, 30000],
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rs ' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });

    // Status Pie Chart
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Active', 'Pending', 'Completed', 'Overdue'],
            datasets: [{
                data: [12, 5, 25, 3],
                backgroundColor: [
                    'rgb(75, 192, 192)',
                    'rgb(255, 205, 86)',
                    'rgb(54, 162, 235)',
                    'rgb(255, 99, 132)'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true
        }
    });
</script>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

<style>
    .card {
        transition: transform 0.2s;
    }
    .card:hover {
        transform: translateY(-5px);
    }
</style>
@endsection