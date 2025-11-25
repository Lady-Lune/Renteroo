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

    .item-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
        border-radius: 15px;
    }

    .status-alert {
        border-radius: 15px;
        border: none;
        padding: 1rem;
    }

    .contact-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px;
        padding: 1.5rem;
    }
</style>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2><i class="bi bi-receipt"></i> Rental Details</h2>
            <p class="text-muted mb-0">Rental #{{ $rental->id }}</p>
        </div>
        <a href="{{ route('customer.rentals.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to My Rentals
        </a>
    </div>

    <!-- Status Alert -->
    @if($rental->status == 'active' && $rental->isOverdue())
        <div class="alert alert-danger status-alert">
            <h5><i class="bi bi-exclamation-triangle"></i> Rental Overdue</h5>
            <p class="mb-0">Your rental is {{ $rental->getLateDays() }} day(s) overdue. Please return the item as soon as possible to avoid additional late fees.</p>
        </div>
    @elseif($rental->status == 'active')
        @php $daysLeft = $rental->getDaysUntilReturn(); @endphp
        @if($daysLeft <= 2)
            <div class="alert alert-warning status-alert">
                <h5><i class="bi bi-clock-history"></i> Return Reminder</h5>
                <p class="mb-0">Your rental is due for return in {{ $daysLeft }} day(s). Please plan to return the item on time.</p>
            </div>
        @endif
    @elseif($rental->status == 'completed')
        <div class="alert alert-success status-alert">
            <h5><i class="bi bi-check-circle"></i> Rental Completed</h5>
            <p class="mb-0">Thank you for returning the item on time! We hope you had a great experience.</p>
        </div>
    @elseif($rental->status == 'cancelled')
        <div class="alert alert-secondary status-alert">
            <h5><i class="bi bi-x-circle"></i> Rental Cancelled</h5>
            <p class="mb-0">This rental has been cancelled.</p>
        </div>
    @endif

    <div class="row">
        <!-- Left Column -->
        <div class="col-lg-8">
            <!-- Item & Rental Info -->
            <div class="detail-card">
                <div class="row">
                    <div class="col-md-4">
                        @if($rental->item->image)
                            <img src="{{ asset('storage/' . $rental->item->image) }}" alt="{{ $rental->item->name }}" class="item-image">
                        @else
                            <div class="item-image d-flex align-items-center justify-content-center bg-light">
                                <i class="{{ $rental->item->category->icon }} text-muted" style="font-size: 3rem;"></i>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-8">
                        <h4 class="mb-3">{{ $rental->item->name }}</h4>
                        <p class="text-muted mb-3">
                            <i class="{{ $rental->item->category->icon }}"></i> {{ $rental->item->category->name }}
                        </p>
                        @if($rental->item->description)
                            <p class="mb-0">{{ $rental->item->description }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Rental Details -->
            <div class="detail-card">
                <h5 class="mb-4">Rental Information</h5>
                
                <div class="info-row">
                    <div class="row">
                        <div class="col-md-4 text-muted">Rental Period</div>
                        <div class="col-md-8">
                            <strong>{{ $rental->start_date->format('M d, Y') }} - {{ $rental->end_date->format('M d, Y') }}</strong>
                            <p class="text-muted small mb-0">{{ $rental->getRentalDays() }} days</p>
                        </div>
                    </div>
                </div>

                @if($rental->actual_return_date)
                    <div class="info-row">
                        <div class="row">
                            <div class="col-md-4 text-muted">Actual Return Date</div>
                            <div class="col-md-8">
                                <strong>{{ $rental->actual_return_date->format('M d, Y') }}</strong>
                                @if($rental->actual_return_date->lt($rental->end_date))
                                    <span class="badge bg-success ms-2">Early Return</span>
                                @elseif($rental->actual_return_date->gt($rental->end_date))
                                    <span class="badge bg-danger ms-2">Late Return</span>
                                @else
                                    <span class="badge bg-info ms-2">On Time</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

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
                        <strong>Rental Booked</strong>
                        <p class="text-muted small mb-0">{{ $rental->created_at->format('M d, Y h:i A') }}</p>
                    </div>

                    @if($rental->status == 'active' || $rental->status == 'completed' || $rental->status == 'overdue')
                        <div class="timeline-item">
                            <div class="timeline-icon">
                                <i class="bi bi-box-arrow-right text-primary"></i>
                            </div>
                            <strong>Item Pickup</strong>
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

                    <div class="d-grid gap-2 mb-3">
                        <a href="{{ route('customer.rentals.download-invoice', $rental->id) }}" 
                           class="btn btn-primary"
                           target="_blank">
                            <i class="bi bi-download"></i> Download Invoice PDF
                        </a>
                    </div>

                    @if($rental->invoice->status == 'pending')
                        <div class="alert alert-warning">
                            <small><strong>Payment Due:</strong> Please settle your payment by the due date.</small>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Contact Information -->
            <div class="contact-card">
                <h5 class="mb-3"><i class="bi bi-headset"></i> Need Help?</h5>
                <p class="mb-2">If you have any questions about your rental, please contact us:</p>
                <p class="mb-2"><i class="bi bi-envelope"></i> support@rentarou.com</p>
                <p class="mb-0"><i class="bi bi-telephone"></i> +1-234-567-8900</p>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
@endsection