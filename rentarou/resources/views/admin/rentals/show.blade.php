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

    .info-row {
        padding: 0.75rem 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .timeline {
        position: relative;
        padding-left: 2rem;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 0.5rem;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e9ecef;
    }

    .timeline-item {
        position: relative;
        padding-bottom: 1.5rem;
    }

    .timeline-icon {
        position: absolute;
        left: -1.5rem;
        width: 2rem;
        height: 2rem;
        border-radius: 50%;
        background: white;
        border: 3px solid #667eea;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2><i class="bi bi-receipt"></i> Rental Details</h2>
            <p class="text-muted mb-0">Rental #{{ $rental->id }}</p>
        </div>
        <a href="{{ route('admin.rentals.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to Rentals
        </a>
    </div>

    <div class="row">
        <!-- Left Column -->
        <div class="col-lg-8">
            <!-- Customer & Item Info -->
            <div class="detail-card">
                <h5 class="mb-4">Booking Information</h5>
                
                <div class="info-row">
    <div class="row">
        <div class="col-md-4 text-muted">Customer</div>
        <div class="col-md-8">
            @if($rental->is_guest)
                <strong>{{ $rental->guest_name }}</strong>
                <span class="badge bg-warning text-dark ms-2">Guest</span>
                <p class="text-muted small mb-0">
                    <i class="bi bi-telephone"></i> {{ $rental->guest_phone }}
                </p>
                @if($rental->guest_email)
                    <p class="text-muted small mb-0">
                        <i class="bi bi-envelope"></i> {{ $rental->guest_email }}
                    </p>
                @endif
                <p class="text-muted small mb-0">
                    <i class="bi bi-card-text"></i> ID: {{ $rental->guest_id_number }}
                </p>
            @else
                <strong>{{ $rental->user->name }}</strong>
                <span class="badge bg-primary ms-2">Registered</span>
                <p class="text-muted small mb-0">{{ $rental->user->email }}</p>
            @endif
        </div>
    </div>
</div>

                <div class="info-row">
                    <div class="row">
                        <div class="col-md-4 text-muted">Item</div>
                        <div class="col-md-8">
                            <strong>{{ $rental->item->name }}</strong>
                            <p class="text-muted small mb-0">
                                <i class="{{ $rental->item->category->icon }}"></i> {{ $rental->item->category->name }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="info-row">
                    <div class="row">
                        <div class="col-md-4 text-muted">Rental Period</div>
                        <div class="col-md-8">
                            <strong>{{ $rental->start_date->format('M d, Y') }} - {{ $rental->end_date->format('M d, Y') }}</strong>
                            <p class="text-muted small mb-0">{{ $rental->getRentalDays() }} days</p>
                        </div>
                    </div>
                </div>

                <div class="info-row">
                    <div class="row">
                        <div class="col-md-4 text-muted">Quantity</div>
                        <div class="col-md-8"><strong>{{ $rental->quantity }} unit(s)</strong></div>
                    </div>
                </div>

                <div class="info-row">
                    <div class="row">
                        <div class="col-md-4 text-muted">Status</div>
                        <div class="col-md-8">
                            <span class="badge 
                                @if($rental->status == 'active') bg-success
                                @elseif($rental->status == 'completed') bg-info
                                @elseif($rental->status == 'pending') bg-warning
                                @elseif($rental->status == 'overdue') bg-danger
                                @else bg-secondary
                                @endif">
                                {{ ucfirst($rental->status) }}
                            </span>
                            
                            @if($rental->isOverdue())
                                <span class="badge bg-danger ms-2">{{ $rental->getLateDays() }} days overdue</span>
                            @elseif($rental->status == 'active')
                                @php $daysLeft = $rental->getDaysUntilReturn(); @endphp
                                @if($daysLeft >= 0)
                                    <span class="badge bg-info ms-2">{{ $daysLeft }} days until return</span>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>

                @if($rental->notes)
                    <div class="info-row">
                        <div class="row">
                            <div class="col-md-4 text-muted">Notes</div>
                            <div class="col-md-8">{{ $rental->notes }}</div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Timeline -->
            <div class="detail-card">
                <h5 class="mb-4">Rental Timeline</h5>
                
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-icon">
                            <i class="bi bi-check text-success"></i>
                        </div>
                        <strong>Rental Created</strong>
                        <p class="text-muted small mb-0">{{ $rental->created_at->format('M d, Y h:i A') }}</p>
                    </div>

                    @if($rental->status == 'active' || $rental->status == 'completed' || $rental->status == 'overdue')
                        <div class="timeline-item">
                            <div class="timeline-icon">
                                <i class="bi bi-box-arrow-right text-primary"></i>
                            </div>
                            <strong>Item Picked Up</strong>
                            <p class="text-muted small mb-0">{{ $rental->start_date->format('M d, Y') }}</p>
                        </div>
                    @endif

                    @if($rental->actual_return_date)
                        <div class="timeline-item">
                            <div class="timeline-icon">
                                <i class="bi bi-box-arrow-in-left text-success"></i>
                            </div>
                            <strong>Item Returned</strong>
                            <p class="text-muted small mb-0">{{ $rental->actual_return_date->format('M d, Y') }}</p>
                        </div>
                    @else
                        <div class="timeline-item">
                            <div class="timeline-icon bg-light">
                                <i class="bi bi-clock text-muted"></i>
                            </div>
                            <strong>Expected Return</strong>
                            <p class="text-muted small mb-0">{{ $rental->end_date->format('M d, Y') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-lg-4">
            <!-- Pricing Details -->
            <div class="detail-card">
                <h5 class="mb-4">Pricing Details</h5>
                
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Daily Rate:</span>
                    <strong>Rs {{ number_format($rental->daily_rate, 2) }}</strong>
                </div>
                
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Number of Days:</span>
                    <strong>{{ $rental->getRentalDays() }}</strong>
                </div>
                
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Quantity:</span>
                    <strong>{{ $rental->quantity }}</strong>
                </div>
                
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Subtotal:</span>
                    <strong>Rs {{ number_format($rental->total_amount, 2) }}</strong>
                </div>

                @if($rental->late_fee > 0)
                    <div class="d-flex justify-content-between mb-2 text-danger">
                        <span>Late Fee:</span>
                        <strong>Rs {{ number_format($rental->late_fee, 2) }}</strong>
                    </div>
                @endif

                @if($rental->damage_fee > 0)
                    <div class="d-flex justify-content-between mb-3 text-danger">
                        <span>Damage Fee:</span>
                        <strong>Rs {{ number_format($rental->damage_fee, 2) }}</strong>
                    </div>
                @endif

                <hr>

                <div class="d-flex justify-content-between">
                    <h5>Total Amount:</h5>
                    <h4 class="text-primary">Rs {{ number_format($rental->total_amount + $rental->late_fee + $rental->damage_fee, 2) }}</h4>
                </div>
            </div>

            <!-- Invoice -->
            @if($rental->invoice)
                <div class="detail-card">
                    <h5 class="mb-3">Invoice</h5>
                    
                    <p class="mb-2">
                        <strong>Invoice #:</strong> {{ $rental->invoice->invoice_number }}
                    </p>
                    
                    <p class="mb-2">
                        <strong>Status:</strong>
                        <span class="badge 
                            @if($rental->invoice->status == 'paid') bg-success
                            @elseif($rental->invoice->status == 'pending') bg-warning
                            @else bg-danger
                            @endif">
                            {{ ucfirst($rental->invoice->status) }}
                        </span>
                    </p>
                    
                    <p class="mb-3">
                        <strong>Due Date:</strong> {{ $rental->invoice->due_date->format('M d, Y') }}
                    </p>

                    <a href="#" class="btn btn-outline-primary w-100 mb-2">
                        <i class="bi bi-file-pdf"></i> Download Invoice
                    </a>
                    
                    <a href="#" class="btn btn-outline-success w-100">
                        <i class="bi bi-envelope"></i> Email Invoice
                    </a>
                </div>
            @endif

            <!-- Actions -->
            <div class="d-flex flex-column gap-2">
                @if($rental->status == 'pending')
                    <button class="btn btn-success">
                        <i class="bi bi-check-circle"></i> Confirm Rental
                    </button>
                @endif

                @if($rental->status == 'active')
                    <button class="btn btn-info">
                        <i class="bi bi-box-arrow-in-left"></i> Mark as Returned
                    </button>
                @endif

                @if($rental->status != 'cancelled')
                    <button class="btn btn-danger">
                        <i class="bi bi-x-circle"></i> Cancel Rental
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
@endsection