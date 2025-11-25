@extends('layouts.app')

@section('content')
<style>
    .invoice-container {
        background: white;
        border-radius: 10px;
        padding: 2rem;
        box-shadow: 0 2px 15px rgba(0,0,0,0.08);
    }

    .invoice-header {
        border-bottom: 2px solid #f0f0f0;
        padding-bottom: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .invoice-title {
        color: #667eea;
        font-size: 2.5rem;
        font-weight: bold;
    }

    .invoice-details {
        background: #f8f9fa;
        border-left: 4px solid #667eea;
        padding: 1.5rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
    }

    .invoice-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 1rem;
        font-size: 0.95rem;
    }

    .invoice-row strong {
        color: #333;
    }

    .status-badge {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 25px;
        font-weight: 600;
        font-size: 0.85rem;
    }

    .status-badge.paid {
        background: #d4edda;
        color: #155724;
    }

    .status-badge.pending {
        background: #fff3cd;
        color: #856404;
    }

    .status-badge.overdue {
        background: #f8d7da;
        color: #721c24;
    }

    .line-item {
        padding: 1rem 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .line-item:last-child {
        border-bottom: none;
    }

    .totals {
        background: #f8f9fa;
        padding: 1.5rem;
        border-radius: 8px;
        margin-top: 1.5rem;
    }

    .total-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.75rem;
        font-size: 1rem;
    }

    .total-row.final {
        font-size: 1.25rem;
        font-weight: bold;
        color: #667eea;
        border-top: 2px solid #ddd;
        padding-top: 1rem;
        margin-top: 1rem;
    }

    .payment-info {
        background: #e8f4f8;
        border-left: 4px solid #17a2b8;
        padding: 1rem;
        border-radius: 8px;
        margin-top: 1.5rem;
    }

    .mark-paid-form {
        background: #f8f9fa;
        padding: 1.5rem;
        border-radius: 8px;
        margin-top: 1.5rem;
    }
</style>

<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <a href="{{ route('invoices.index') }}" class="text-decoration-none">
                <i class="bi bi-arrow-left"></i> Back to Invoices
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="invoice-container">
        <!-- Header -->
        <div class="invoice-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="invoice-title">Invoice</div>
                    <h5 class="text-muted mb-0">{{ $invoice->invoice_number }}</h5>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="status-badge {{ strtolower($invoice->status) }}">
                        {{ ucfirst($invoice->status) }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Invoice Details -->
        <div class="invoice-details">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <strong>Invoice Date:</strong><br>
                        {{ $invoice->created_at->format('M d, Y') }}
                    </div>
                    <div class="mb-3">
                        <strong>Due Date:</strong><br>
                        {{ $invoice->due_date ? $invoice->due_date->format('M d, Y') : 'N/A' }}
                    </div>
                </div>
                <div class="col-md-6">
                    @if($invoice->status === 'paid' && $invoice->paid_date)
                        <div class="mb-3">
                            <strong>Paid Date:</strong><br>
                            {{ $invoice->paid_date->format('M d, Y') }}
                        </div>
                    @endif
                    @if($invoice->transaction_id)
                        <div class="mb-3">
                            <strong>Transaction ID:</strong><br>
                            <code>{{ $invoice->transaction_id }}</code>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Rental and Item Details -->
        <div class="mb-4">
            <h5>Rental Details</h5>
            <div class="line-item">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <strong>Item:</strong> {{ $invoice->rental->item->title }}<br>
                        <small class="text-muted">
                            Category: {{ $invoice->rental->item->category->name ?? 'N/A' }}<br>
                            Rental Period: {{ $invoice->rental->start_date->format('M d') }} - {{ $invoice->rental->end_date->format('M d, Y') }}
                        </small>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <strong>${{ number_format($invoice->rental->item->price_per_day, 2) }}/day</strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pricing Breakdown -->
        <div class="totals">
            <div class="total-row">
                <span>Subtotal:</span>
                <strong>${{ number_format($invoice->subtotal, 2) }}</strong>
            </div>
            @if($invoice->late_fee && $invoice->late_fee > 0)
                <div class="total-row">
                    <span>Late Fee:</span>
                    <strong class="text-danger">${{ number_format($invoice->late_fee, 2) }}</strong>
                </div>
            @endif
            @if($invoice->damage_fee && $invoice->damage_fee > 0)
                <div class="total-row">
                    <span>Damage Fee:</span>
                    <strong class="text-danger">${{ number_format($invoice->damage_fee, 2) }}</strong>
                </div>
            @endif
            <div class="total-row final">
                <span>Total Amount Due:</span>
                <strong>${{ number_format($invoice->total, 2) }}</strong>
            </div>
        </div>

        <!-- Payment Info -->
        @if($invoice->status === 'paid')
            <div class="payment-info">
                <i class="bi bi-check-circle"></i> <strong>Payment Received</strong><br>
                <small>This invoice has been marked as paid on {{ $invoice->paid_date->format('M d, Y') }}</small>
            </div>
        @else
            <div class="payment-info">
                <i class="bi bi-exclamation-circle"></i> <strong>Payment Pending</strong><br>
                <small>This invoice is awaiting payment. Please contact the admin to process payment.</small>
            </div>
        @endif

        <!-- Admin Actions -->
        @if(auth()->user()->isAdmin() && $invoice->status !== 'paid')
            <div class="mark-paid-form">
                <h6 class="mb-3">Mark as Paid</h6>
                <form action="{{ route('invoices.mark-paid', $invoice->id) }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="payment_method" class="form-label">Payment Method</label>
                            <select name="payment_method" id="payment_method" class="form-control" required>
                                <option value="">-- Select Payment Method --</option>
                                <option value="manual">Manual Payment</option>
                                <option value="transfer">Bank Transfer</option>
                                <option value="check">Check</option>
                                <option value="cash">Cash</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="transaction_id" class="form-label">Transaction ID (Optional)</label>
                            <input type="text" name="transaction_id" id="transaction_id" class="form-control" placeholder="e.g., TXN123456">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle"></i> Mark Invoice as Paid
                    </button>
                </form>
            </div>
        @endif

        <!-- Action Buttons -->
        <div class="mt-4">
            <a href="{{ route('invoices.download', $invoice->id) }}" class="btn btn-outline-secondary">
                <i class="bi bi-download"></i> Download PDF
            </a>
            @if(!auth()->user()->isAdmin() && $invoice->rental)
                <a href="{{ route('customer.rentals.show', $invoice->rental->id) }}" class="btn btn-outline-primary">
                    <i class="bi bi-box"></i> View Rental Details
                </a>
            @elseif(auth()->user()->isAdmin() && $invoice->rental)
                <a href="{{ route('admin.rentals.show', $invoice->rental->id) }}" class="btn btn-outline-primary">
                    <i class="bi bi-box"></i> View Rental Details
                </a>
            @endif
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
@endsection
