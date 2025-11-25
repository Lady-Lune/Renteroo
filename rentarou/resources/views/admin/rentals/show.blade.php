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

                    <a href="{{ route('invoices.show', $rental->invoice->id) }}" class="btn btn-outline-primary w-100 mb-2">
                        <i class="bi bi-eye"></i> View Invoice
                    </a>

                    <a href="{{ route('invoices.download', $rental->invoice->id) }}" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-file-pdf"></i> Download PDF
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
                    <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#returnModal">
                        <i class="bi bi-box-arrow-in-left"></i> Mark as Returned
                    </button>
                @endif

                @if($rental->status != 'cancelled' && $rental->status != 'completed')
                    <form action="{{ route('admin.rentals.cancel', $rental->id) }}" method="POST" 
                          onsubmit="return confirm('Are you sure you want to cancel this rental? This action cannot be undone and the item availability will be restored.')"
                          style="display: inline;">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="bi bi-x-circle"></i> Cancel Rental
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Return Modal -->
<div class="modal fade" id="returnModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-box-arrow-in-left"></i> Mark Rental as Returned
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.rentals.return', $rental->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Return Date *</label>
                        <input type="date" class="form-control" name="return_date" 
                               value="{{ date('Y-m-d') }}" 
                               min="{{ $rental->start_date->format('Y-m-d') }}" 
                               max="{{ date('Y-m-d') }}"
                               required>
                        <div class="form-text">
                            Original rental period: {{ $rental->start_date->format('M d') }} - {{ $rental->end_date->format('M d, Y') }}
                            ({{ $rental->getRentalDays() }} days)
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Damage Assessment</label>
                        <select class="form-select" name="damage_condition" onchange="toggleDamageFee(this)">
                            <option value="good">Good Condition - No Damage</option>
                            <option value="minor">Minor Damage - Small Fee</option>
                            <option value="major">Major Damage - Large Fee</option>
                        </select>
                    </div>

                    <div class="mb-3" id="damageDetails" style="display: none;">
                        <label class="form-label">Damage Description</label>
                        <textarea class="form-control" name="damage_notes" rows="3" 
                                  placeholder="Describe the damage..."></textarea>
                    </div>

                    <div class="mb-3" id="damageFeeSection" style="display: none;">
                        <label class="form-label">Damage Fee (Rs)</label>
                        <input type="number" class="form-control" name="damage_fee" 
                               min="0" step="0.01" placeholder="0.00">
                    </div>

                    <div class="alert alert-info">
                        <strong><i class="bi bi-info-circle"></i> Note:</strong>
                        <ul class="mb-0 mt-2">
                            <li>If returned early, a refund will be calculated automatically</li>
                            <li>If returned late, late fees will be applied (50% of daily rate per day)</li>
                            <li>Item availability will be restored upon return</li>
                            <li>Invoice will be updated to reflect actual charges</li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle"></i> Mark as Returned
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function toggleDamageFee(select) {
        const damageDetails = document.getElementById('damageDetails');
        const damageFeeSection = document.getElementById('damageFeeSection');
        
        if (select.value === 'good') {
            damageDetails.style.display = 'none';
            damageFeeSection.style.display = 'none';
        } else {
            damageDetails.style.display = 'block';
            damageFeeSection.style.display = 'block';
            
            // Set suggested damage fee based on condition
            const damageFeeInput = document.querySelector('input[name="damage_fee"]');
            if (select.value === 'minor') {
                damageFeeInput.value = '{{ $rental->daily_rate * 2 }}'; // 2 days worth
            } else if (select.value === 'major') {
                damageFeeInput.value = '{{ $rental->daily_rate * 7 }}'; // 1 week worth
            }
        }
    }
</script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
@endsection